using Microsoft.Maps.MapControl.WPF;
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
    /// Interaction logic for OntkoppelWindow.xaml
    /// </summary>
    public partial class OntkoppelWindow : Window
    {
        private Database dbConnection = new Database();

        private DispatcherTimer timerDatabase = new DispatcherTimer();

        private DataTable dtStatus = null;

        private string sCurrentChauffeurID = null;

        private Pushpin pushpinChauffeur = null;
        private Pushpin pushpinKlant = null;

        public OntkoppelWindow()
        {
            InitializeComponent();

            this.MaxHeight = SystemParameters.MaximizedPrimaryScreenHeight;

            timerDatabase.Interval = new TimeSpan(0, 0, 1);
            timerDatabase.Tick += TimerDatabase_Tick;
            timerDatabase.Start();

            updateTaxiAanvragen();
        }

        private void TimerDatabase_Tick(object sender, EventArgs e)
        {
            timerDatabase.Stop();

            DataTable dataTable = dbConnection.runSelectQuery("SELECT taxi_aanvraag.*, klant.* FROM taxi_aanvraag INNER JOIN klant ON taxi_aanvraag.klantID = klant.id;");

            if (dtStatus == null)
            {
                dtStatus = dataTable;
            }

            if (dbConnection.compareDatatables(dtStatus, dataTable))
            {
                btnReload.Visibility = Visibility.Collapsed;
                timerDatabase.Start();
            }
            else
            {
                btnReload.Visibility = Visibility.Visible;
            }
            dtStatus = dataTable;
        }

        private void btnBack_Click(object sender, RoutedEventArgs e)
        {
            this.Close();
        }

        private void btnReload_Click(object sender, RoutedEventArgs e)
        {
            updateTaxiAanvragen();

            btnReload.Visibility = Visibility.Collapsed;

            timerDatabase.Start();
        }

        private void Window_Closed(object sender, EventArgs e)
        {
            DashboardWindow dashboard = new DashboardWindow();
            dashboard.Show();
        }

        private void updateInformatie(string sAanvraagID)
        {
            if (sAanvraagID != null)
            {
                DataTable datatable = dbConnection.getTaxiAanvraag(sAanvraagID);

                sCurrentChauffeurID = datatable.Rows[0]["chauffeurID"].ToString();

                tbAantalPassagiers.Text = datatable.Rows[0]["passagiers"].ToString();
                tbLaadruimte.Text = datatable.Rows[0]["minimale_laadruimte"].ToString();
                tbMobielNummer.Text = datatable.Rows[0]["mobiel"].ToString();
                tbDatum.Text = datatable.Rows[0]["datum"].ToString();
                tbTijd.Text = datatable.Rows[0]["tijd"].ToString();
                tbEmail.Text = datatable.Rows[0]["email"].ToString();

                tbChauffeurNaam.Text = datatable.Rows[0]["chauffeurNaam"].ToString();

                double.TryParse(datatable.Rows[0]["latitude"].ToString(), out double latitude);
                double.TryParse(datatable.Rows[0]["longitude"].ToString(), out double longitude);

                double.TryParse(datatable.Rows[0]["latitudeChauffeur"].ToString(), out double latitudeChauffeur);
                double.TryParse(datatable.Rows[0]["longitudeChauffeur"].ToString(), out double longitudeChauffeur);

                addPointOnMap(pushpinKlant, latitude, longitude, Brushes.Green, datatable.Rows[0]["naam"].ToString());
                addPointOnMap(pushpinChauffeur, latitudeChauffeur, longitudeChauffeur, Brushes.Blue, datatable.Rows[0]["naam"].ToString());

                bingMaps.Center = dbConnection.middleOfLocations(new Location(latitude, longitude), new Location(latitudeChauffeur, longitudeChauffeur));
                bingMaps.ZoomLevel = 13;
            }
            else
            {
                sCurrentChauffeurID = null;

                tbAantalPassagiers.Text = "";
                tbLaadruimte.Text = "";
                tbMobielNummer.Text = "";
                tbDatum.Text = "";
                tbTijd.Text = "";
                tbEmail.Text = "";
                tbChauffeurNaam.Text = "";
            }
        }

        private void updateTaxiAanvragen()
        {
            dtStatus = null;

            DataTable dataTable = dbConnection.getGeaccepteerdeTaxiAanvragen();

            DataRow dataRow = dataTable.NewRow();
            dataRow[0] = 0;
            dataRow[1] = "Kies een aanvraag";

            dataTable.Rows.InsertAt(dataRow, 0);

            cbTaxiAanvragen.ItemsSource = dataTable.DefaultView;

            cbTaxiAanvragen.SelectedIndex = 0;
        }
        
        private void addPointOnMap(Pushpin pushpin, double latitude, double longitude, Brush color, string sName)
        {
            if (pushpin == null)
            {
                pushpin = new Pushpin();
                bingMaps.Children.Add(pushpin);
            }

            Location location = new Location(latitude, longitude);
            pushpin.Location = location;
            cXml xml = new cXml();
            string sAddress = xml.ReverseGeocode(latitude.ToString().Replace(",", "."), longitude.ToString().Replace(",", "."));
            pushpin.ToolTip = "Naam: " + sName + "\nAdres: " + sAddress;
            pushpin.Background = color;
        }

        private void cbTaxiAanvragen_SelectionChanged(object sender, SelectionChangedEventArgs e)
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

        private void btnOntkoppelChauffeur_Click(object sender, RoutedEventArgs e)
        {
            if (sCurrentChauffeurID != null) {
                if (cbTaxiAanvragen.SelectedIndex != 0 && cbTaxiAanvragen.SelectedValue != null)
                {
                    if (dbConnection.ontkoppelChauffeur(cbTaxiAanvragen.SelectedValue.ToString(), sCurrentChauffeurID))
                    {
                        updateTaxiAanvragen();

                        MessageBox.Show("Chauffeur succesvol geontkoppelt van de taxi aanvraag", "Succesvol Geontkoppelt", MessageBoxButton.OK, MessageBoxImage.Information);
                    }
                    else
                    {
                        MessageBox.Show("Kon de chauffeur niet ontkoppelen van de taxi aanvraag", "Ontkoppel fout", MessageBoxButton.OK, MessageBoxImage.Error);
                    }
                }
            }
        }
    }
}
