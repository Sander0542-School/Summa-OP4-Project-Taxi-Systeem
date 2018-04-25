<!-- Navbar -->

    <nav<?php if ($pageTitle != "Home") { echo ' class="margin-bottom-50px"'; } ?>>
      <ul class="nav nav-pills justify-content-end">
        <li class="nav-item">
          <a class="nav-link<?php if (isset($pageTitle) && $pageTitle == "Home") { echo " active"; } ?>" href="/home">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if (isset($pageTitle) && $pageTitle == "Chauffeurs") { echo " active"; } ?>" href="/chauffeur">Chauffeurs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if (isset($pageTitle) && $pageTitle == "Klanten") { echo " active"; } ?>" href="/klanten">Klanten</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if (isset($pageTitle) && $pageTitle == "Contact") { echo " active"; } ?>" href="/contact">Contact</a>
        </li>
<?php
if ($CORE->is_logged_in()) {
  echo '
        <li class="nav-item">
          <a class="nav-link'; if (isset($pageTitle) && ($pageTitle == "Klant Profiel" || $pageTitle == "Chauffeur Profiel")) { echo " active"; } echo '" href="/klant-profiel">Profiel</a>
        </li>';
} else {
  echo '
        <li class="nav-item">
          <a class="nav-link'; if (isset($pageTitle) && $pageTitle == "Inloggen") { echo " active"; } echo '" href="/inloggen">Inloggen</a>
        </li>';
}
?>
      </ul>
    </nav>