using Microsoft.Maps.MapControl.WPF;
using MySql.Data.MySqlClient;

using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace IXAT
{
    class Database
    {
        //private MySqlConnection connection = new MySqlConnection("Server=sanderjochems.nl;Database=ixat_taxis;Uid=ixat;Pwd=QzA4AWAFBgdhS3FZ;SslMode=none");
        private MySqlConnection connection = new MySqlConnection("Server=localhost;Database=ixat_taxis;Uid=root;Pwd=;SslMode=none");

        public DataTable runSelectQuery(string query)
        {
            connection.Open();

            MySqlCommand sqlCommand = connection.CreateCommand();
            sqlCommand.CommandText = query;

            MySqlDataReader dataReader = sqlCommand.ExecuteReader();

            DataTable dataTable = new DataTable();
            dataTable.Load(dataReader);

            connection.Close();

            return dataTable;
        }

        public bool Login(string sUsername, string sPassword)
        {
            bool bResult = false;

            try
            {
                connection.Open();

                MySqlCommand sqlCommand = connection.CreateCommand();
                sqlCommand.CommandText = "SELECT * FROM klant WHERE gebruikersnaam = @username AND wachtwoord = @password AND applicatie = '1'";
                sqlCommand.Parameters.AddWithValue("@username", sUsername);
                sqlCommand.Parameters.AddWithValue("@password", sPassword);

                MySqlDataReader dataReader = sqlCommand.ExecuteReader();

                bResult = dataReader.HasRows;
            }
            catch (Exception)
            {
                //Do nothing
            }
            finally
            {
                connection.Close();
            }

            return bResult;
        }

        public DataTable getChauffeurAanvraag(string sKlantID)
        {
            connection.Open();

            MySqlCommand sqlCommand = connection.CreateCommand();
            sqlCommand.CommandText = "SELECT chauffeur_aanvraag.*, klant.naam, klant.mobiel, klant.email FROM chauffeur_aanvraag INNER JOIN klant ON chauffeur_aanvraag.klantID = klant.id WHERE chauffeur_aanvraag.klantID = @klantID;";
            sqlCommand.Parameters.AddWithValue("@klantID", sKlantID);
            MySqlDataReader dataReader = sqlCommand.ExecuteReader();

            DataTable dataTable = new DataTable();
            dataTable.Load(dataReader);

            connection.Close();

            return dataTable;
        }

        public bool deleteChauffeurAanvraag(string sKlantID)
        {
            bool bResult;

            try
            {
                connection.Open();

                MySqlCommand sqlCommand = connection.CreateCommand();
                sqlCommand.CommandText = "DELETE FROM chauffeur_aanvraag WHERE klantID = @klantID";
                sqlCommand.Parameters.AddWithValue("@klantID", sKlantID);

                sqlCommand.ExecuteNonQuery();

                bResult = true;
            }
            catch (Exception ex)
            {
                bResult = false;
            }
            finally
            {
                connection.Close();
            }

            return bResult;
        }

        public bool acceptChauffeurAanvraag(string sKlantID)
        {
            bool bResult;

            try
            {
                connection.Open();

                MySqlCommand sqlCommand = connection.CreateCommand();
                sqlCommand.CommandText = "INSERT INTO chauffeur (automerk, autotype, kenteken, aantal_passagiers, laadruimte, schadevrije_jaren, rijbewijs) SELECT automerk, autotype, kenteken, aantal_passagiers, laadruimte, schadevrije_jaren, rijbewijs FROM chauffeur_aanvraag WHERE chauffeur_aanvraag.klantID = @klantID;";
                sqlCommand.Parameters.AddWithValue("@klantID", sKlantID);
                sqlCommand.ExecuteNonQuery();

                MySqlCommand sqlCommand2 = connection.CreateCommand();
                sqlCommand2.CommandText = "UPDATE klant SET chauffeurID = @chauffeurID WHERE id = @klantID";
                sqlCommand2.Parameters.AddWithValue("@chauffeurID", sqlCommand.LastInsertedId);
                sqlCommand2.Parameters.AddWithValue("@klantID", sKlantID);
                sqlCommand2.ExecuteNonQuery();


                MySqlCommand sqlCommand3 = connection.CreateCommand();
                sqlCommand3.CommandText = "DELETE FROM chauffeur_aanvraag WHERE klantID = @klantID";
                sqlCommand3.Parameters.AddWithValue("@klantID", sKlantID);
                sqlCommand3.ExecuteNonQuery();

                bResult = true;
            }
            catch (Exception ex)
            {
                bResult = false;
            }
            finally
            {
                connection.Close();
            }

            return bResult;
        }

        public DataTable getChauffeurAanvragen()
        {
            connection.Open();

            MySqlCommand sqlCommand = connection.CreateCommand();
            sqlCommand.CommandText = "SELECT klantID as id, klant.naam FROM chauffeur_aanvraag INNER JOIN klant ON chauffeur_aanvraag.klantID = klant.id";
            MySqlDataReader dataReader = sqlCommand.ExecuteReader();

            DataTable dataTable = new DataTable();
            dataTable.Load(dataReader);

            connection.Close();

            return dataTable;
        }

        public DataTable getTaxiAanvraagLocations()
        {
            connection.Open();

            MySqlCommand sqlCommand = connection.CreateCommand();
            sqlCommand.CommandText = "SELECT klantID, klant.naam, latitude, longitude FROM taxi_aanvraag INNER JOIN klant ON taxi_aanvraag.klantID = klant.id";
            MySqlDataReader dataReader = sqlCommand.ExecuteReader();

            DataTable dtKlanten = new DataTable();
            dtKlanten.Load(dataReader);

            connection.Close();

            return dtKlanten;
        }

        public DataTable getChauffeurLocations()
        {
            connection.Open();

            MySqlCommand sqlCommand = connection.CreateCommand();
            sqlCommand.CommandText = "SELECT klant.id as klantID, naam, chauffeur.latitude, chauffeur.longitude FROM klant INNER JOIN chauffeur ON klant.chauffeurID = chauffeur.id WHERE NOT klant.chauffeurID is null";
            MySqlDataReader dataReader = sqlCommand.ExecuteReader();

            DataTable dtChauffeurs = new DataTable();
            dtChauffeurs.Load(dataReader);

            connection.Close();

            return dtChauffeurs;
        }

        public bool compareDatatables(DataTable dataTableOld, DataTable dataTableNew)
        {
            if (dataTableOld != null && dataTableNew != null)
            {
                if (dataTableOld.Rows.Count == dataTableNew.Rows.Count && dataTableOld.Columns.Count == dataTableNew.Columns.Count)
                {
                    for (int iRow = 0; iRow < dataTableOld.Rows.Count; iRow++)
                    {
                        for (int iColumn = 0; iColumn < dataTableOld.Columns.Count; iColumn++)
                        {
                            if (dataTableOld.Rows[iRow][iColumn].ToString() != dataTableNew.Rows[iRow][iColumn].ToString())
                            {
                                return false;
                            }
                        }
                    }
                    return true;
                }
            }
            return false;
        }

        public DataTable getTaxiAanvragen()
        {
            connection.Open();

            MySqlCommand sqlCommand = connection.CreateCommand();
            sqlCommand.CommandText = "SELECT aanvraagID as id, klant.naam FROM taxi_aanvraag INNER JOIN klant ON klant.id = taxi_aanvraag.klantID INNER JOIN chauffeur WHERE klaar = '0';";
            MySqlDataReader dataReader = sqlCommand.ExecuteReader();

            DataTable dataTable = new DataTable();
            dataTable.Load(dataReader);

            connection.Close();

            return dataTable;
        }

        public DataTable getGeaccepteerdeTaxiAanvragen()
        {
            connection.Open();

            MySqlCommand sqlCommand = connection.CreateCommand();
            sqlCommand.CommandText = "SELECT aanvraagID as id, klant.naam FROM taxi_aanvraag INNER JOIN klant ON klant.id = taxi_aanvraag.klantID WHERE taxi_aanvraag.chauffeurID IS NOT NULL AND klaar = '0';";
            MySqlDataReader dataReader = sqlCommand.ExecuteReader();

            DataTable dataTable = new DataTable();
            dataTable.Load(dataReader);

            connection.Close();

            return dataTable;
        }

        public DataTable getTaxiAanvraag(string sAanvraagID)
        {
            connection.Open();

            MySqlCommand sqlCommand = connection.CreateCommand();
            //TODO: DEZE QUERY WERKT NIET!!!
            sqlCommand.CommandText = "SELECT minimale_laadruimte, passagiers, TIME(datum_tijd) as tijd, CONCAT(DAY(datum_tijd),'-',MONTH(datum_tijd),'-',YEAR(datum_tijd)) as datum, klant.naam, klant.email, klant.mobiel, taxi_aanvraag.latitude, taxi_aanvraag.longitude, chauffeurKlant.naam as chauffeurNaam, chauffeurKlant.id as chauffeurID, chauffeur.latitude as latitudeChauffeur, chauffeur.longitude as longitudeChauffeur FROM taxi_aanvraag LEFT JOIN klant ON klant.id = taxi_aanvraag.klantID LEFT JOIN klant as chauffeurKlant ON taxi_aanvraag.chauffeurID = klant.id LEFT JOIN chauffeur ON chauffeurKlant.chauffeurID = chauffeur.id WHERE aanvraagID = @id AND klaar = '0';";
            sqlCommand.Parameters.AddWithValue("@id", sAanvraagID);
            MySqlDataReader dataReader = sqlCommand.ExecuteReader();

            DataTable dataTable = new DataTable();
            dataTable.Load(dataReader);

            connection.Close();

            return dataTable;
        }

        public DataTable getVrijeChauffeurs()
        {
            connection.Open();

            MySqlCommand sqlCommand = connection.CreateCommand();
            sqlCommand.CommandText = "SELECT chauffeur.id, klant.naam, latitude, longitude FROM klant INNER JOIN chauffeur ON klant.chauffeurID = chauffeur.id WHERE chauffeur.vrij = 1;";
            
            MySqlDataReader dataReader = sqlCommand.ExecuteReader();

            DataTable dataTable = new DataTable();
            dataTable.Load(dataReader);

            connection.Close();

            return dataTable;
        }

        public bool koppelTaxiAanvraag(string sAanvraagID, string sChauffeurID)
        {
            bool bResult;

            try
            {
                connection.Open();

                MySqlCommand sqlCommand = connection.CreateCommand();
                sqlCommand.CommandText = "UPDATE chauffeur SET chauffeur.vrij = 0 WHERE chauffeur.id = @chauffeurID";
                sqlCommand.Parameters.AddWithValue("@chauffeurID", sChauffeurID);
                sqlCommand.ExecuteNonQuery();

                MySqlCommand sqlCommand2 = connection.CreateCommand();
                sqlCommand2.CommandText = "UPDATE taxi_aanvraag SET chauffeurID = @chauffeurID WHERE aanvraagID = @aanvraagID;";
                sqlCommand2.Parameters.AddWithValue("@chauffeurID", sChauffeurID);
                sqlCommand2.Parameters.AddWithValue("@aanvraagID", sAanvraagID);
                sqlCommand2.ExecuteNonQuery();

                bResult = true;
            }
            catch (Exception ex)
            {
                bResult = false;
            }
            finally
            {
                connection.Close();
            }

            return bResult;
        }

        public bool ontkoppelChauffeur(string sAanvraagID, string sChauffeurID)
        {
            bool bResult;
            
            try
            {
                connection.Open();

                MySqlCommand sqlCommand = connection.CreateCommand();
                sqlCommand.CommandText = "UPDATE taxi_aanvraag SET taxi_aanvraag.geaccepteerd = 0, chauffeurID = NULL WHERE aanvraagID = @aanvraagID;";
                sqlCommand.Parameters.AddWithValue("@aanvraagID", sAanvraagID);
                sqlCommand.ExecuteNonQuery();

                MySqlCommand sqlCommand2 = connection.CreateCommand();
                sqlCommand2.CommandText = "UPDATE chauffeur SET chauffeur.vrij = 0 WHERE chauffeur.id = @chauffeurID";
                sqlCommand2.Parameters.AddWithValue("@chauffeurID", sChauffeurID);
                sqlCommand2.ExecuteNonQuery();

                bResult = true;

            }
            catch (Exception ex)
            {
                bResult = false;
            }
            finally
            {
                connection.Close();
            }
            return bResult;
        }

        public Location middleOfLocations(Location location1, Location location2)
        {
            return new Location((location1.Latitude + location2.Latitude) / 2, (location1.Longitude + location2.Longitude) / 2);
        }
    }
}
