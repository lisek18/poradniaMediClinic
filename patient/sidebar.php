<ul>
          <li><a href="profil.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : '' ?>">Moje dane</a></li>
          <li><a href="edit_profile.php" class="<?= basename($_SERVER['PHP_SELF']) == 'edit_profile.php' ? 'active' : '' ?>">Edytuj dane</a></li>
          <li><a href="zmiana-hasla.php" class="<?= basename($_SERVER['PHP_SELF']) == 'zmiana-hasla.php' ? 'active' : '' ?>">Zmień hasło</a></li>
          <li><a href="add_visit.php" class="<?= basename($_SERVER['PHP_SELF']) == 'add_visit.php' ? 'active' : '' ?>">Dodaj wizytę</a></li>
          <li><a href="my_visits.php" class="<?= basename($_SERVER['PHP_SELF']) == 'my_visits.php' ? 'active' : '' ?>">Moje wizyty</a></li>
          <li><a href="upload_documents.php" class="<?= basename($_SERVER['PHP_SELF']) == 'upload_documents.php' ? 'active' : '' ?>">Wgraj dokumenty</a></li>
          <li><a href="my_documents.php" class="<?= basename($_SERVER['PHP_SELF']) == 'my_documents.php' ? 'active' : '' ?>">Moje dokumenty</a></li>
          <li><a href="chat.php" class="<?= basename($_SERVER['PHP_SELF']) == 'chat.php' ? 'active' : '' ?>">Czat z lekarzami</a></li>


          <li><a href="/poradnia/logout.php">Wyloguj</a></li>
        </ul>