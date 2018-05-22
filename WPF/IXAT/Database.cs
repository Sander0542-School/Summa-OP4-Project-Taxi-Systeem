﻿using MySql.Data.MySqlClient;

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
        private MySqlConnection connection = new MySqlConnection("Server=localhost;Database=ixat_taxis;Uid=root;Pwd=;SslMode=none");

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
                sqlCommand.CommandText = "INSERT INTO chauffeur (automerk, autotype, kenteken, aantal_passagiers, laadruimte, schadevrije_jaren) SELECT automerk, autotype, kenteken, aantal_passagiers, laadruimte, schadevrije_jaren FROM chauffeur_aanvraag WHERE chauffeur_aanvraag.klantID = @klantID;";
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
    }
}
