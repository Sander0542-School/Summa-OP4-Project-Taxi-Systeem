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
    /// Interaction logic for OntkoppelWindow.xaml
    /// </summary>
    public partial class OntkoppelWindow : Window
    {
        private Database dbConnection = new Database();

        public OntkoppelWindow()
        {
            InitializeComponent();

            this.MaxHeight = SystemParameters.MaximizedPrimaryScreenHeight;
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
                tbChauffeurNaam.Text = "";
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

            dataTable.Rows.InsertAt(dataRow, 0);

            //tbChauffeurNaam.Text = dbConnection.g

        }
    }
}
