using System;
using System.Collections.Generic;
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
using System.Windows.Navigation;
using System.Windows.Shapes;

namespace IXAT
{
    /// <summary>
    /// Interaction logic for DashboardWindow.xaml
    /// </summary>
    public partial class DashboardWindow : Window
    {
        public DashboardWindow()
        {
            InitializeComponent();
        }

        private void btnAanvragen_Click(object sender, RoutedEventArgs e)
        {
            AanvragenWindow aanvragen = new AanvragenWindow();
            aanvragen.Show();
            this.Close();
        }

        private void btnKoppelen_Click(object sender, RoutedEventArgs e)
        {
            KoppelWindow koppel = new KoppelWindow();
            koppel.Show();
            this.Close();
        }

        private void btnOntkoppelen_Click(object sender, RoutedEventArgs e)
        {
            OntkoppelWindow ontkoppel = new OntkoppelWindow();
            ontkoppel.Show();
            this.Close();
        }
    }
}
