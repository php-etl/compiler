<?php declare(strict_types=1);

namespace functional\Constraint\Pipeline;

use Kiboko\Component\PHPUnitExtension\Constraint\Pipeline\IteratesLike;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\TestCase;

final class IteratesLikeTest extends TestCase
{
    private static string $fixtures = <<<JSON
            {"firstName":"Margot","lastName":"Lamy","email":"denis.marcelle@example.net","address":"39, chemin Huet\\r\\n76 185 Gomez","postcode":"84 138","city":"Legrand"}
            {"firstName":"Dominique","lastName":"Garnier","email":"christine87@example.net","address":"46, chemin de Hernandez\\r\\n52 853 Munoz","postcode":"95 866","city":"BuissonBourg"}
            {"firstName":"Mich\\u00e8le","lastName":"Martins","email":"valerie.lopez@example.org","address":"374, rue de Gauthier\\r\\n82315 LaineBourg","postcode":"89 150","city":"Aubry"}
            {"firstName":"Olivie","lastName":"Maillot","email":"jacques.guilbert@example.org","address":"5, impasse Valentine Guerin\\r\\n33828 Goncalves-sur-Martineau","postcode":"62365","city":"Blanchard"}
            {"firstName":"Timoth\\u00e9e","lastName":"Rey","email":"guichard.olivie@example.org","address":"70, place de Boulanger\\r\\n93 372 Becker-sur-Faure","postcode":"44 565","city":"Lecomteboeuf"}
            {"firstName":"Anouk","lastName":"Allard","email":"danielle.martin@example.net","address":"46, impasse Anne Dumas\\r\\n51132 Dijoux","postcode":"54 491","city":"Pires"}
            {"firstName":"Zo\\u00e9","lastName":"Goncalves","email":"weiss.frederic@example.org","address":"53, rue de Roche\\r\\n77 698 Hoareau","postcode":"47275","city":"Martinez-sur-Valette"}
            {"firstName":"Bernard","lastName":"Vasseur","email":"clement.cecile@example.net","address":"rue de Schneider\\r\\n33723 Monnier","postcode":"95215","city":"TanguyVille"}
            {"firstName":"Alexandre","lastName":"Roger","email":"glegrand@example.net","address":"259, rue Maurice\\r\\n19 223 Blanchet-sur-Mer","postcode":"56777","city":"GuillotVille"}
            {"firstName":"St\\u00e9phane","lastName":"Brunel","email":"suzanne.rodriguez@example.org","address":"3, rue Bourgeois\\r\\n25243 Briand-sur-Mer","postcode":"70657","city":"Joseph"}
            {"firstName":"Nicolas","lastName":"Petitjean","email":"gregoire.alexandria@example.net","address":"64, rue Christophe Le Roux\\r\\n51 824 JosephVille","postcode":"75 373","city":"Renard-sur-Laine"}
            {"firstName":"Corinne","lastName":"Noel","email":"brunet.anouk@example.com","address":"46, rue de Bonneau\\r\\n40730 Grenier-sur-Vaillant","postcode":"43278","city":"Lesage"}
            {"firstName":"\\u00c9dith","lastName":"Blot","email":"nicolas52@example.net","address":"102, impasse Antoinette Rey\\r\\n02 302 Caron","postcode":"48048","city":"Pereiranec"}
            {"firstName":"Nathalie","lastName":"Perrier","email":"zacharie21@example.net","address":"43, rue de Lefort\\r\\n57 648 Jacques-sur-Paul","postcode":"13501","city":"Bouchet-sur-Mercier"}
            {"firstName":"Camille","lastName":"Dupuis","email":"clerc.robert@example.com","address":"6, rue Leclerc\\r\\n90253 RodriguezBourg","postcode":"75 328","city":"Morel"}
            {"firstName":"Honor\\u00e9","lastName":"Pires","email":"gfischer@example.org","address":"8, boulevard de Marin\\r\\n99104 Cohennec","postcode":"78650","city":"Verdierdan"}
            {"firstName":"Benjamin","lastName":"Mallet","email":"mbourdon@example.net","address":"impasse de Gimenez\\r\\n36908 Pruvost-la-For\\u00eat","postcode":"05 781","city":"Brun"}
            {"firstName":"Marcel","lastName":"Normand","email":"qbruneau@example.net","address":"51, avenue Aim\\u00e9e Leleu\\r\\n07220 Meyer-sur-Meyer","postcode":"85 936","city":"Guichard"}
            {"firstName":"Christophe","lastName":"Hubert","email":"mary.dorothee@example.com","address":"52, avenue Honor\\u00e9 Hernandez\\r\\n09786 Thomasboeuf","postcode":"82 155","city":"RousselVille"}
            {"firstName":"Nicole","lastName":"Seguin","email":"susan.carpentier@example.com","address":"2, place Traore\\r\\n02530 Vincent","postcode":"37899","city":"Gauthier"}
            {"firstName":"Tristan","lastName":"Brunel","email":"valerie06@example.org","address":"331, rue J\\u00e9r\\u00f4me Germain\\r\\n56723 Blinboeuf","postcode":"09896","city":"Traore"}
            {"firstName":"Jacques","lastName":"Fournier","email":"gabrielle57@example.org","address":"place de Boyer\\r\\n24799 Letellier-sur-Duval","postcode":"87650","city":"Arnaud"}
            {"firstName":"Gilles","lastName":"Laporte","email":"charrier.edith@example.org","address":"72, place Ferreira\\r\\n95 799 Roussel-la-For\\u00eat","postcode":"82552","city":"CarreBourg"}
            {"firstName":"Margaret","lastName":"Prevost","email":"olivier.reynaud@example.com","address":"avenue Thomas Gregoire\\r\\n38253 Legrand-la-For\\u00eat","postcode":"04 725","city":"Richard-sur-Colas"}
            {"firstName":"Gilles","lastName":"Jacob","email":"francois.adam@example.org","address":"3, avenue Fr\\u00e9d\\u00e9ric Seguin\\r\\n56 042 Guibertboeuf","postcode":"90 383","city":"Valentinboeuf"}
            {"firstName":"Agathe","lastName":"Chevalier","email":"dbailly@example.org","address":"91, impasse Dominique Jacquot\\r\\n93 237 Riviere","postcode":"82 121","city":"Nicolas-sur-Guichard"}
            {"firstName":"Vincent","lastName":"Brunel","email":"hcoste@example.org","address":"9, avenue V\\u00e9ronique Masse\\r\\n90236 Fischer-la-For\\u00eat","postcode":"93376","city":"Allain"}
            {"firstName":"Bertrand","lastName":"Duval","email":"verdier.patricia@example.net","address":"27, rue Rossi\\r\\n82826 Arnaudboeuf","postcode":"17101","city":"Girard"}
            {"firstName":"\\u00c9dith","lastName":"Teixeira","email":"diane.hoareau@example.org","address":"98, place H\\u00e9l\\u00e8ne Baron\\r\\n53652 Guichard","postcode":"02742","city":"Boulanger-les-Bains"}
            {"firstName":"Nicolas","lastName":"Tessier","email":"cpetitjean@example.org","address":"15, impasse de Dijoux\\r\\n23829 Camusboeuf","postcode":"83728","city":"Hardyboeuf"}
            {"firstName":"Laure","lastName":"Bonneau","email":"rklein@example.net","address":"boulevard de Pelletier\\r\\n26 649 Letellierdan","postcode":"93 884","city":"Le Goff"}
            {"firstName":"Paul","lastName":"Jacques","email":"elise.fischer@example.com","address":"42, impasse Guichard\\r\\n65 557 Nguyen-sur-Rossi","postcode":"80231","city":"Dupont-sur-Mer"}
            {"firstName":"Antoinette","lastName":"Peltier","email":"yclement@example.net","address":"2, chemin de Chauvet\\r\\n04 170 Guibert","postcode":"46 818","city":"Maillet"}
            {"firstName":"No\\u00e9mi","lastName":"Lamy","email":"stephane56@example.net","address":"10, rue de Riou\\r\\n46561 Hoarau","postcode":"83000","city":"Da Silva"}
            {"firstName":"Alexandrie","lastName":"Coste","email":"besson.margaud@example.net","address":"rue Traore\\r\\n79 043 Leclercq","postcode":"46888","city":"Humbert"}
            {"firstName":"Hugues","lastName":"Poirier","email":"amercier@example.net","address":"4, avenue de Lamy\\r\\n78067 Roche","postcode":"96966","city":"Martineaunec"}
            {"firstName":"Gabriel","lastName":"Moreno","email":"gregoire.rousseau@example.org","address":"87, place Delahaye\\r\\n23 300 Baron","postcode":"18271","city":"SchmittBourg"}
            {"firstName":"H\\u00e9l\\u00e8ne","lastName":"Traore","email":"victoire.jacob@example.com","address":"11, avenue de Boulanger\\r\\n73931 Dias","postcode":"83 269","city":"Moulin"}
            {"firstName":"Fr\\u00e9d\\u00e9ric","lastName":"Rousset","email":"maurice04@example.net","address":"rue Robert Le Goff\\r\\n06 224 Evrard","postcode":"37 580","city":"Chevallier-sur-Richard"}
            {"firstName":"Ren\\u00e9","lastName":"Charles","email":"capucine07@example.com","address":"338, rue \\u00c9lise Ollivier\\r\\n29559 Chevallier","postcode":"96 669","city":"Morvanboeuf"}
            {"firstName":"Nicole","lastName":"Blanc","email":"fleblanc@example.net","address":"26, boulevard Lucie Arnaud\\r\\n64 933 Leger-sur-Mer","postcode":"17330","city":"Vasseur"}
            {"firstName":"Emmanuel","lastName":"Ruiz","email":"jacqueline.pruvost@example.org","address":"99, chemin de Ferrand\\r\\n20174 Bernierdan","postcode":"05 710","city":"Humbertnec"}
            {"firstName":"Th\\u00e9ophile","lastName":"Leroy","email":"amelie.pires@example.com","address":"652, impasse Alexandria Clement\\r\\n72070 Blanchet","postcode":"01 737","city":"Launay"}
            {"firstName":"Christiane","lastName":"Deschamps","email":"arthur.germain@example.net","address":"5, impasse de Guillet\\r\\n39901 Collet","postcode":"20 641","city":"Guillot"}
            {"firstName":"Laurence","lastName":"Leblanc","email":"michel.noel@example.org","address":"69, place Fr\\u00e9d\\u00e9rique Moreau\\r\\n23 301 Leroynec","postcode":"30 680","city":"Seguin"}
            {"firstName":"Lorraine","lastName":"Pereira","email":"juliette.blin@example.org","address":"18, rue de Ferrand\\r\\n08 159 Jolyboeuf","postcode":"38512","city":"Grondin"}
            {"firstName":"Thibault","lastName":"Collin","email":"gvincent@example.org","address":"rue Roy\\r\\n97 151 Legendre","postcode":"23978","city":"Raynaud"}
            {"firstName":"Julie","lastName":"Fournier","email":"barbe.audrey@example.net","address":"44, avenue Leblanc\\r\\n86738 Gay-sur-Gerard","postcode":"68412","city":"Roche"}
            {"firstName":"Claire","lastName":"Roy","email":"laurent.colette@example.com","address":"87, rue Julie Imbert\\r\\n53 062 Lopes-sur-Ruiz","postcode":"50062","city":"Pereira-sur-Marin"}
            {"firstName":"Ad\\u00e8le","lastName":"Meunier","email":"mathieu.jerome@example.org","address":"67, rue Lecoq\\r\\n12477 Marechaldan","postcode":"54 811","city":"Dialloboeuf"}
            {"firstName":"Michelle","lastName":"Roux","email":"susan32@example.org","address":"2, rue de Lemonnier\\r\\n54 725 Leconteboeuf","postcode":"76446","city":"Marchal"}
            {"firstName":"Madeleine","lastName":"Leduc","email":"antoine44@example.com","address":"71, impasse de Mahe\\r\\n09543 ClementBourg","postcode":"76 451","city":"Briandnec"}
            {"firstName":"Laetitia","lastName":"Perrin","email":"qcharpentier@example.com","address":"26, chemin Costa\\r\\n44 930 Costa","postcode":"39127","city":"Prevostnec"}
            {"firstName":"Susanne","lastName":"Leclerc","email":"brunel.elisabeth@example.net","address":"62, boulevard Caron\\r\\n47397 Bernier","postcode":"59019","city":"Peron-les-Bains"}
            {"firstName":"Colette","lastName":"Roy","email":"collin.lucie@example.com","address":"71, rue de Robert\\r\\n13767 Morvan","postcode":"93634","city":"Rousset"}
            {"firstName":"Christophe","lastName":"Henry","email":"imeunier@example.net","address":"4, rue Ribeiro\\r\\n96 831 Bigot-la-For\\u00eat","postcode":"93 651","city":"Martel-sur-Mer"}
            {"firstName":"Gr\\u00e9goire","lastName":"Bouvier","email":"emile.etienne@example.org","address":"212, rue Christiane Gomez\\r\\n54593 Morin","postcode":"66629","city":"LevequeBourg"}
            {"firstName":"Nicolas","lastName":"Mace","email":"vidal.sabine@example.com","address":"366, place Bertin\\r\\n95 324 Clerc","postcode":"20 195","city":"Leleu"}
            {"firstName":"Dominique","lastName":"Legrand","email":"lorraine98@example.org","address":"rue St\\u00e9phane Schneider\\r\\n37 804 Moreno","postcode":"21 864","city":"Benoit"}
            {"firstName":"Monique","lastName":"Blanchet","email":"franck86@example.net","address":"57, avenue Allain\\r\\n16 907 Bigot","postcode":"86953","city":"RobertVille"}
            {"firstName":"Gilbert","lastName":"Mathieu","email":"egrondin@example.net","address":"19, impasse de Lamy\\r\\n36 994 Lopes","postcode":"25124","city":"Schneider-sur-Leduc"}
            {"firstName":"Nathalie","lastName":"Auger","email":"corinne.maurice@example.com","address":"73, rue Masse\\r\\n45145 Munoz","postcode":"43 824","city":"Bruneau"}
            {"firstName":"No\\u00e9mi","lastName":"Sauvage","email":"paulette.besson@example.org","address":"72, place de Herve\\r\\n56 108 Couturierdan","postcode":"29144","city":"Arnaud"}
            {"firstName":"Gilbert","lastName":"Michaud","email":"theophile.mallet@example.com","address":"6, impasse Besson\\r\\n11 739 Pelletier","postcode":"65492","city":"MenardBourg"}
            {"firstName":"\\u00c9lodie","lastName":"Diaz","email":"jledoux@example.net","address":"63, rue Alves\\r\\n59906 ChartierBourg","postcode":"54887","city":"Jacques-la-For\\u00eat"}
            {"firstName":"Adrienne","lastName":"Lelievre","email":"eugene.philippe@example.com","address":"2, impasse Masson\\r\\n24934 Pierre","postcode":"49 838","city":"Munozboeuf"}
            {"firstName":"Georges","lastName":"Roy","email":"wwagner@example.com","address":"408, place de Raymond\\r\\n52 986 Renaud","postcode":"68 831","city":"Leclerc"}
            {"firstName":"No\\u00e9mi","lastName":"Guillaume","email":"tgauthier@example.net","address":"757, rue Mich\\u00e8le Gay\\r\\n65212 Leroux","postcode":"95030","city":"Ruiz"}
            JSON;

    private static function fromJSONStream(string $source): iterable
    {
        $file = new \SplFileObject('php://temp', 'r+');
        $file->fwrite($source);
        $file->fseek(0, SEEK_SET);

        while (!$file->eof()) {
            yield \json_decode($file->fgets(), true, 512, JSON_THROW_ON_ERROR);
        }
    }

    public static function successfulDataProvider(): \Generator
    {
        yield [
            IteratesLikeTest::fromJSONStream(self::$fixtures),
            IteratesLikeTest::fromJSONStream(self::$fixtures),
        ];

        yield [
            [],
            [],
        ];
    }

    #[DataProvider('successfulDataProvider')]
    public function testExtractionSucceeds(iterable $expected, iterable $actual)
    {
        $constraint = new IteratesLike($expected, fn ($value) => new IsEqual($value));

        $this->assertThat($actual, $constraint);
    }

    public function testExtractionFails()
    {
        $constraint = new IteratesLike(IteratesLikeTest::fromJSONStream(self::$fixtures), fn ($value) => new LogicalNot(new IsEqual($value)));

        $this->assertThat([], $constraint);
    }
}
