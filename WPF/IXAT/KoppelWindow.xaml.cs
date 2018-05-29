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

namespace IXAT
{
    /// <summary>
    /// Interaction logic for KoppelWindow.xaml
    /// </summary>
    public partial class KoppelWindow : Window
    {
        private Database dbConnection = new Database();

        public KoppelWindow()
        {
            InitializeComponent();

            this.MaxHeight = SystemParameters.MaximizedPrimaryScreenHeight;

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
            }
            else
            {
                tbAantalPassagiers.Text = "";
                tbLaadruimte.Text = "";
                tbMobielNummer.Text = "";
                tbDatum.Text = "";
                tbTijd.Text = "";
                tbEmail.Text = "";
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
            DataTable dataTable = dbConnection.getVrijeChauffeurs();

            DataRow dataRow = dataTable.NewRow();
            dataRow[0] = 0;
            dataRow[1] = "Kies een vrije chauffeur";

            dataTable.Rows.InsertAt(dataRow, 0);

            cbChauffeurNaam.ItemsSource = dataTable.DefaultView;

            cbChauffeurNaam.SelectedIndex = 0;
        }

        private void cbChauffeurNaam_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {

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
                        MessageBox.Show("Succesvol gekoppeld");

                        updateTaxiAanvragen();
                        updateVrijeChauffeurs();
                    }
                    else
                    {

                    }
                }
            }
        }
    }
}
