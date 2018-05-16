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
            dataRow[1] = "Aanvragen";

            dataTable.Rows.InsertAt(dataRow, 0);
            
            cbRequests.ItemsSource = dataTable.DefaultView;
            
            cbRequests.SelectedIndex = 0;
        }

        private void updateInformatie(string sKlantID)
        {
            if (sKlantID != null)
            {

            }
            else
            {

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

            if (comboBox.SelectedValue.ToString() != "0")
            {
                updateInformatie(comboBox.SelectedValue.ToString());
            }
            else
            {
                updateInformatie(null);
            }
        }
    }
}
