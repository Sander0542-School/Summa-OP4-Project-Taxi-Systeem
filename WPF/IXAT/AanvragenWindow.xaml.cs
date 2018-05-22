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
using System.Windows.Threading;

namespace IXAT
{
    /// <summary>
    /// Interaction logic for AanvragenWindow.xaml
    /// </summary>
    public partial class AanvragenWindow : Window
    {
        private Database dbConnection = new Database();

        private DispatcherTimer timerDatabase = new DispatcherTimer();

        private string sCurrentKlantID = null;

        private DataTable dtStatus = null;

        public AanvragenWindow()
        {
            InitializeComponent();

            this.MaxHeight = SystemParameters.MaximizedPrimaryScreenHeight;

            timerDatabase.Interval = new TimeSpan(0, 0, 1);
            timerDatabase.Tick += TimerDatabase_Tick;
            timerDatabase.Start();

            updateChauffeurAanvragen();
        }

        private void TimerDatabase_Tick(object sender, EventArgs e)
        {
            timerDatabase.Stop();

            DataTable dataTable = dbConnection.runSelectQuery("SELECT * FROM chauffeur_aanvraag");

            if (dataTable == dtStatus || dtStatus == null)
            {
                btnReload.Visibility = Visibility.Collapsed;
                dtStatus = dataTable;
                timerDatabase.Start();
            }
            else
            {
                btnReload.Visibility = Visibility.Visible;
            }
        }

        private void updateChauffeurAanvragen()
        {
            dtStatus = null;
            cbRequests.SelectedIndex = 0;

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

            if (comboBox.SelectedIndex != 0 && comboBox.SelectedValue != null)
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
            updateChauffeurAanvragen();
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
            updateChauffeurAanvragen();
        }

        private void btnReload_Click(object sender, RoutedEventArgs e)
        {
            updateChauffeurAanvragen();

            btnReload.Visibility = Visibility.Collapsed;

            timerDatabase.Start();
        }
    }
}
