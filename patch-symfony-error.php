<?php
# Sinds php 8.4 is de constante E_STRICT deprecated en geeft gebruik daarvan een warning.
# Om deze warning weg te halen, kun je natuurlijk die error_reporting in je php.ini
# aanpassen, maar om te voorkomen dat iedere student hier vragen over gaat stellen hebben
# we dit scriptje gemaakt.
# Dit wordt automatisch aangeroepen vanuit de composer.json.

$filepath = __DIR__ . '/vendor/symfony/error-handler/ErrorHandler.php';

print "Verwijderen van de E_STRICT in ErrorHandler.php\n";

if (!file_exists($filepath)) {
    print "ErrorHandler.php niet gevonden.\n";
    exit(1);
}

$content = file_get_contents($filepath);

// Remove the line with E_STRICT
$patched = preg_replace('/^.*E_STRICT.*$\n?/m', '', $content, -1);

if ($patched === null) {
    print "Regex niet gevonden; bestand niet aangepast.\n";
    exit(1);
}

file_put_contents($filepath, $patched);
print "ErrorHandler.php aangepast.\n";
