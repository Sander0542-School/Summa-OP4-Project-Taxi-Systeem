using Microsoft.Maps.MapControl.WPF;
using Microsoft.Maps.MapControl.WPF.Design;

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
    /// Interaction logic for KoppelWindow.xaml
    /// </summary>
    public partial class KoppelWindow : Window
    {
        private Database dbConnection = new Database();

        private DispatcherTimer timerDatabase = new DispatcherTimer();

        private DataTable dtStatus = null;

        private DataTable dataTableChauffeurs;

        private Location locationCurrentKlant = null;

        public KoppelWindow()
        {
            InitializeComponent();

            this.MaxHeight = SystemParameters.MaximizedPrimaryScreenHeight;

            timerDatabase.Interval = new TimeSpan(0, 0, 1);
            timerDatabase.Tick += TimerDatabase_Tick;
            timerDatabase.Start();

            updateTaxiAanvragen();
            updateVrijeChauffeurs();

            DataTable dtKlantLocations = dbConnection.getTaxiAanvraagLocations();

            foreach (DataRow row in dtKlantLocations.Rows)
            {
                double.TryParse(row["latitude"].ToString(), out double latitude);
                double.TryParse(row["longitude"].ToString(), out double longitude);

                addPointOnMap(latitude, longitude, Brushes.Green, row["naam"].ToString());
            }

            DataTable dtChauffeurLocations = dbConnection.getChauffeurLocations();

            foreach (DataRow row in dtChauffeurLocations.Rows)
            {
                double.TryParse(row["latitude"].ToString(), out double latitude);
                double.TryParse(row["longitude"].ToString(), out double longitude);

                addPointOnMap(latitude, longitude, Brushes.Blue, row["naam"].ToString());
            }
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

        private void updateInformatie(string sAanvraagID)
        {
            if (sAanvraagID != null)
            {
                DataTable datatable = dbConnection.getTaxiAanvraag(sAanvraagID);

                tbAantalPassagiers.Text = datatable.Rows[0]["passagiers"].ToString();
                tbLaadruimte.Text = datatable.Rows[0]["minimale_laadruimte"].ToString();
                tbMobielNummer.Text = datatable.Rows[0]["mobiel"].ToString();
                tbDatum.Text = datatable.Rows[0]["datum"].ToString();
                tbTijd.Text = datatable.Rows[0]["tijd"].ToString();
                tbEmail.Text = datatable.Rows[0]["email"].ToString();

                double.TryParse(datatable.Rows[0]["latitude"].ToString(), out double latitude);
                double.TryParse(datatable.Rows[0]["longitude"].ToString(), out double longitude);

                locationCurrentKlant = new Location(latitude, longitude);

                bingMaps.Center = locationCurrentKlant;
                bingMaps.ZoomLevel = 17;

                updateVrijeChauffeurs();
            }
            else
            {
                tbAantalPassagiers.Text = "";
                tbLaadruimte.Text = "";
                tbMobielNummer.Text = "";
                tbDatum.Text = "";
                tbTijd.Text = "";
                tbEmail.Text = "";

                locationCurrentKlant = null;
            }
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
        private void updateTaxiAanvragen()
        {
            dtStatus = null;

            DataTable dataTable = dbConnection.getTaxiAanvragen();

            DataRow dataRow = dataTable.NewRow();
            dataRow[0] = 0;
            dataRow[1] = "Kies een aanvraag";

            dataTable.Rows.InsertAt(dataRow, 0);

            cbTaxiAanvragen.ItemsSource = dataTable.DefaultView;

            cbTaxiAanvragen.SelectedIndex = 0;
        }

        private void updateVrijeChauffeurs()
        {
            dataTableChauffeurs = dbConnection.getVrijeChauffeurs();

            DataRow dataRow = dataTableChauffeurs.NewRow();
            dataRow[0] = 0;
            dataRow[1] = "Kies een vrije chauffeur";
            dataRow[2] = 0;
            dataRow[3] = 0;

            dataTableChauffeurs.Rows.InsertAt(dataRow, 0);

            cbChauffeurNaam.ItemsSource = dataTableChauffeurs.DefaultView;

            cbChauffeurNaam.SelectedIndex = 0;
        }

        private void addPointOnMap(double latitude, double longitude, Brush color, string sName)
        {
            Location location = new Location(latitude, longitude);
            Pushpin pushpin = new Pushpin();
            pushpin.Location = location;
            cXml xml = new cXml();
            string sAddress = xml.ReverseGeocode(latitude.ToString().Replace(",", "."), longitude.ToString().Replace(",", "."));
            pushpin.ToolTip = "Naam: " + sName + "\nAdres: " + sAddress;
            pushpin.Background = color;

            bingMaps.Children.Add(pushpin);
        }

        private void btnKoppelChauffeur_Click(object sender, RoutedEventArgs e)
        {
            if (cbChauffeurNaam.SelectedIndex != 0 && cbChauffeurNaam.SelectedValue != null)
            {
                if (cbTaxiAanvragen.SelectedIndex != 0 && cbTaxiAanvragen.SelectedValue != null)
                {
                    if (dbConnection.koppelTaxiAanvraag(cbTaxiAanvragen.SelectedValue.ToString(), cbChauffeurNaam.SelectedValue.ToString()))
                    {
                        updateTaxiAanvragen();
                        updateVrijeChauffeurs();

                        MessageBox.Show("De taxi aanvraag is succesvol gekoppeld aan de chauffeur", "Succesvol gekoppeld", MessageBoxButton.OK, MessageBoxImage.Information);
                    }
                    else
                    {
                        MessageBox.Show("Kon de taxi aanvraag niet koppelen aan de chauffeur", "Koppel fout", MessageBoxButton.OK, MessageBoxImage.Error);
                    }
                }
            }
        }

        private void cbChauffeurNaam_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            if (cbChauffeurNaam.SelectedIndex != 0 && cbChauffeurNaam.SelectedValue != null && locationCurrentKlant != null)
            {
                double.TryParse(dataTableChauffeurs.Rows[cbChauffeurNaam.SelectedIndex]["latitude"].ToString(), out double latitude1);
                double.TryParse(dataTableChauffeurs.Rows[cbChauffeurNaam.SelectedIndex]["longitude"].ToString(), out double longitude1);

                bingMaps.Center = dbConnection.middleOfLocations(new Location(latitude1, longitude1), locationCurrentKlant);
                bingMaps.ZoomLevel = 13;
            }
        }
    }
}
