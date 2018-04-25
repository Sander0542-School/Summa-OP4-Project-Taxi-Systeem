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
  </body>
</html>