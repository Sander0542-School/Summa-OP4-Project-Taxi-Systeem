using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Xml;

namespace IXAT
{
    class cXml
    {
        public string ReverseGeocode(string sLatitude, string sLongitude)
        {
            try
            {
                using (var client = new WebClient())
                {
                    string queryString = "http://dev.virtualearth.net/REST/v1/Locations/" + sLatitude + "," + sLongitude + "?o=xml&key=AkfnB70S4jexw95YfQIkm-4FSK3x25_UFRyhJ4AIY1TjaNRrXgrJMnzrL54-YV6m";

                    // Reader for the onlinge xml document
                    XmlTextReader reader = new XmlTextReader(queryString);
                    DataSet dsTest = new DataSet();
                    dsTest.ReadXml(reader);
                    return dsTest.Tables["Address"].Rows[0]["FormattedAddress"].ToString();

                }
            }
            catch (Exception ex)
            {
                return "";
            }
        }
    }
}