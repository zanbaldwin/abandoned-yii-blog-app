<?php

    class m131208_163922_homepage_translations_nl extends CDbMigration
    {

        /**
         * Migrate: Up
         *
         * @access public
         * @return void
         */
    	public function up()
    	{
            $this->insert('{{translations}}', array(
                'id'            => 1,
                'language'      => 'nl',
                'translation'   => 'Welkom bij <i>{name}</i>',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 2,
                'language'      => 'nl',
                'translation'   => 'Gefeliciteerd! Je hebt met succes je Yii toepassing.',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 3,
                'language'      => 'nl',
                'translation'   => 'U mag de inhoud van deze pagina wijzigen door de volgende twee bestanden:',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 4,
                'language'      => 'nl',
                'translation'   => 'Bekijk file: <code>{file}</code>',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 5,
                'language'      => 'nl',
                'translation'   => 'Layout-bestand: <code>{file}</code>',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 6,
                'language'      => 'nl',
                'translation'   => 'documentatie',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 7,
                'language'      => 'nl',
                'translation'   => 'Voor meer informatie over hoe u deze applicatie verder te ontwikkelen, lees dan de {documentation}.',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 8,
                'language'      => 'nl',
                'translation'   => 'forum',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 9,
                'language'      => 'nl',
                'translation'   => 'Voel je vrij om te vragen in het {forum}, moet je vragen hebt.',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 10,
                'language'      => 'nl',
                'translation'   => 'Reperkt Gebied',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 11,
                'language'      => 'nl',
                'translation'   => 'Role-based Access Control',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 12,
                'language'      => 'nl',
                'translation'   => 'RBAC',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 13,
                'language'      => 'nl',
                'translation'   => 'Dit project heeft nu {rbac} setup. Volgens uw huidige machtigingen u:',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 14,
                'language'      => 'nl',
                'translation'   => 'Controleren toestemming "{permission}"',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 15,
                'language'      => 'nl',
                'translation'   => '<strong>kan geen</strong> toegang tot het beperkte gebied. Sorry.',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 16,
                'language'      => 'nl',
                'translation'   => 'Thuis',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 17,
                'language'      => 'nl',
                'translation'   => 'Login',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 18,
                'language'      => 'nl',
                'translation'   => 'Afmelden ({name})',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 19,
                'language'      => 'nl',
                'translation'   => 'Copyright &copy; {year} door {company}.',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 20,
                'language'      => 'nl',
                'translation'   => 'Alle rechten voorbehouden.',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 21,
                'language'      => 'nl',
                'translation'   => 'toegang tot het {restrictedarea}.',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 22,
                'language'      => 'nl',
                'translation'   => 'Geef uw inloggegevens.',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 23,
                'language'      => 'nl',
                'translation'   => 'Voer je gebruikersnaam, het is niet hoofdlettergevoelig...',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 24,
                'language'      => 'nl',
                'translation'   => 'Geef je wachtwoord, het is hoofdlettergevoelig.',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 25,
                'language'      => 'nl',
                'translation'   => 'Gebruikersnaam',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 26,
                'language'      => 'nl',
                'translation'   => 'Passwoord',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 27,
                'language'      => 'nl',
                'translation'   => 'De opgegeven gebruikersnaam bestaat niet.',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 28,
                'language'      => 'nl',
                'translation'   => 'Het ingevoerde wachtwoord is onjuist.',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 29,
                'language'      => 'nl',
                'translation'   => 'reperkt gebied',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 30,
                'language'      => 'nl',
                'translation'   => 'Sorry dat ik je moet teleurstellen, maar dit beperkte gebied toont alleen af van de mogelijkheden van de {rbac}-systeem het huidige project werktuigen.',
            ));
            $this->insert('{{translations}}', array(
                'id'            => 31,
                'language'      => 'nl',
                'translation'   => 'Niets te zien hier, doorlopen!',
            ));
        }

        /**
         * Migrate: Down
         *
         * @access public
         * @return void
         */
    	public function down()
    	{
            $this->delete('{{translations}}');
    	}

    }
