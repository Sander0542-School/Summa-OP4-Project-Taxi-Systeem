using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Shapes;

namespace IXAT
{
    /// <summary>
    /// Interaction logic for AanvragenWindow.xaml
    /// </summary>
    public partial class AanvragenWindow : Window
    {
        private Database dbConnection = new Database();

        private string sCurrentKlantID = null;

        public AanvragenWindow()
        {
            InitializeComponent();

            this.MaxHeight = SystemParameters.MaximizedPrimaryScreenHeight;

            updateChauffeurAanvragen();
        }

        private void updateChauffeurAanvragen()
        {
            DataTable dataTable = dbConnection.getChauffeurAanvragen();

            DataRow dataRow = dataTable.NewRow();
            dataRow[0] = 0;
            dataRow[1] = "Kies een aanvraag";

            dataTable.Rows.InsertAt(dataRow, 0);
            
            cbRequests.ItemsSource = dataTable.DefaultView;
            
            cbRequests.SelectedIndex = 0;
        }

        private void updateInformatie(string sKlantID)
        {
            if (sKlantID != null)
            {
                DataTable dataTable = dbConnection.getChauffeurAanvraag(sKlantID);

                sCurrentKlantID = sKlantID;

                tbNaam.Text = dataTable.Rows[0]["naam"].ToString();
                tbMobiel.Text = dataTable.Rows[0]["mobiel"].ToString();
                tbEmail.Text = dataTable.Rows[0]["email"].ToString();
                tbAutomerk.Text = dataTable.Rows[0]["automerk"].ToString();
                tbAutotype.Text = dataTable.Rows[0]["autotype"].ToString();
                tbKenteken.Text = dataTable.Rows[0]["kenteken"].ToString();
                tbPassagiers.Text = dataTable.Rows[0]["aantal_passagiers"].ToString();
                tbLaadruimte.Text = dataTable.Rows[0]["laadruimte"].ToString();
                tbSchadevrij.Text = dataTable.Rows[0]["schadevrije_jaren"].ToString();
            }
            else
            {
                sCurrentKlantID = null;

                tbNaam.Text = "";
                tbMobiel.Text = "";
                tbEmail.Text = "";
                tbAutomerk.Text = "";
                tbAutotype.Text = "";
                tbKenteken.Text = "";
                tbPassagiers.Text = "";
                tbLaadruimte.Text = "";
                tbSchadevrij.Text = "";
            }
        }

        private void btnBack_Click(object sender, RoutedEventArgs e)
        {
            DashboardWindow dashboard = new DashboardWindow();
            dashboard.Show();
            this.Close();
        }

        private void Window_Closed(object sender, EventArgs e)
        {
            DashboardWindow dashboard = new DashboardWindow();
            dashboard.Show();
        }

        private void cbRequests_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            ComboBox comboBox = (ComboBox)sender;

            if (comboBox.SelectedIndex != 0)
            {
                updateInformatie(comboBox.SelectedValue.ToString());
            }
            else
            {
                updateInformatie(null);
            }
        }

        private void btnAccept_Click(object sender, RoutedEventArgs e)
        {
            if (sCurrentKlantID != null)
            {
                if (dbConnection.acceptChauffeurAanvraag(sCurrentKlantID))
                {
                    MessageBox.Show("Aanvraag succesvol geaccepteerd", "Succes", MessageBoxButton.OK, MessageBoxImage.Information);
                }
                else
                {
                    MessageBox.Show("Kon de aanvraag niet accepteren", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
                }
            }
        }

        private void btnReject_Click(object sender, RoutedEventArgs e)
        {
            if (sCurrentKlantID != null)
            {
                if (dbConnection.deleteChauffeurAanvraag(sCurrentKlantID))
                {
                    MessageBox.Show("Aanvraag succesvol geweigert", "Succes", MessageBoxButton.OK, MessageBoxImage.Information);
                }
                else
                {
                    MessageBox.Show("Kon de aanvraag niet weigeren", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
                }
            }
        }














        public DataTable getTaxiAanvragen()
        {
            connection.Open();

            MySqlCommand sqlCommand = connection.CreateCommand();
            sqlCommand.CommandText = "SELECT aanvraagID as id, klant.naam FROM taxi_aanvraag INNER JOIN klant ON klant.id = taxi_aanvraag.klantID WHERE klaar = '0';";
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
            sqlCommand.CommandText = "SELECT minimale_laadruimte, passagiers, TIME(datum_tijd) as tijd, CONCAT(DAY(datum_tijd),'-',MONTH(datum_tijd),'-',YEAR(datum_tijd)) as datum, email, mobiel FROM taxi_aanvraag INNER JOIN klant ON klant.id = taxi_aanvraag.klantID WHERE aanvraagID = @id AND klaar = '0';";
            sqlCommand.Parameters.AddWithValue("@id", sAanvraagID);
            MySqlDataReader dataReader = sqlCommand.ExecuteReader();

            DataTable dataTable = new DataTable();
            dataTable.Load(dataReader);

            connection.Close();

            return dataTable;
        }
    }
}
