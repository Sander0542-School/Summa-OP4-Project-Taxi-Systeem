    <script>
      document.addEventListener("DOMContentLoaded", function(event) {
        var footer = document.getElementById("footer");
        if (document.body.clientHeight > window.innerHeight) {
          footer.style.position = 'relative';
        } else {
          footer.style.position = 'fixed';
        }
      });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhUaFv5qwATzUG_DlgxbNCH1wXBa-B-PQ&callback=myMap"></script>
  </body>
</html>