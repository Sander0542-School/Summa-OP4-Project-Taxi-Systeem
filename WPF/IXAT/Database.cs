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
