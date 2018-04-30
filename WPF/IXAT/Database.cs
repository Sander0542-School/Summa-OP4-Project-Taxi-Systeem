using MySql.Data.MySqlClient;

using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace IXAT
{
    class Database
    {
        private MySqlConnection _conn = new MySqlConnection("Server=localhost;Database=ixat_taxis;Uid=root;Pwd=");

        public bool Login(string sUsername, string sPassword)
        {
            bool bResult = false;

            try
            {
                _conn.Open();

                MySqlCommand sqlCommand = _conn.CreateCommand();
                sqlCommand.CommandText = "SELECT * FROM klant WHERE gebruikersnaam = @username AND wachtwoord = @password";
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
                _conn.Close();
            }

            return bResult;
        }
    }
}
