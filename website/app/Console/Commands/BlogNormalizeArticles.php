<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BlogArticle;

class BlogNormalizeArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:normalize-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Normalize articles for markdown support';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Ids of the articles to update
        $ids = [34, 33, 32, 31, 30, 29, 28, 27, 26, 25, 24, 23, 22, 19];

        foreach ($ids as $id) {

            $this->info('Updating content article ' . $id);

            $article = BlogArticle::find($id);

            if ($article !== NULL) {
                $article->content = trim($this->{'article' . $id}());
                $article->save();
                $this->line('Updated !');
            } else {
                $this->error('Can\'t find the article');
            }


        }
    }

    public function article34()
    {
        $string = <<<EOD

## Qui se cache derrière Jock ?
Une PME 100% bordelaise de 60 personnes née en 1938 et qui réalise depuis des préparations gourmandes ! Nous fabriquons et commercialisons des préparations pour gâteaux, des pâtes liquides, des aides à la pâtisserie et petits déjeuners. La boutique propose l’ensemble de la gamme JOCK ainsi que de nombreux lots, idées cadeaux, paniers garnis, objets collectors et produits destockés .

## Quel est ton produit préféré ?
Notre produit le plus ancien et le plus emblématique de la société ! La Crème Jock à la

## Le moment préféré de la journée ?
Le matin quand on met au four les gâteaux et qu'une bonne odeur vient parfumer la boutique.

## Et enfin vos futurs projets ?
On espère pouvoir exporter toujours plus et notamment vers les États-Unis !

## Une marque à retrouver :
Dans plusieurs de nos boxs de l'année 2015 et sans doute aussi de 2016 !
Sur facebook: <https://www.facebook.com/Jock-Bordeaux-Boutique-Jock-143294422370407/?fref=ts>
Ouverte à tous du lundi au vendredi de 09h à 17h30 190 Quai de brazza Bordeaux
Contact : Carole Boniface 05 57 77 02 59

EOD;
    return $string;

    }

    public function article33()
    {
        $string = <<<EOD

## Qui se cache derrière tes bijoux ?
Je suis seule à faire mes bijoux qui sont en laque de coquille d'oeufs. C'est une technique asiatique que m'a appris ma mère. Je suis une fan de mode donc quoi de mieux que de créer des bijoux?!

## Comment as-tu choisi ce nom ?
J'ai préféré garder mon prénom et mon nom de famille (Egloff n'est pas un jeu de mot avec egg)! Je n'avais pas envi de me cacher derrière un pseudo.

## Ton moment préféré de la journée ?
Vers 18-19h, c'est à partir de là que je commence à travailler. je suis plus inspirée le soir.

## Et enfin tes futurs projets ?
Sortir une collection 100% argent et laiton plaqué or. Mais ça ne sera pas avant 2 ans je pense.

## Une marque à retrouver :
Dans la box d'Avril 2015 notamment
Sur facebook : <https://www.facebook.com/egloff.candice/?fref=ts>
Et bientôt dans notre e-shop ! ;)

EOD;
    return $string;

    }

    public function article32()
    {
        $string = <<<EOD

## Qui se cache derrière Chic'Cakes ?
Nous sommes deux : Delphine et Gaëlle. Nous avons créé Chic'Cakes car nous sommes passionnées de pâtisseries et entrepreneuses dans l'âme :)

## Comment avez-vous choisi ce nom ?
Nous essayons de rendre nos cakes le plus chic possible donc le nom Chic'Cakes nous a paru une évidence !

## Quel est votre produit préféré ?
Le cupcake nutella Kinder Bueno !

## Votre moment préféré de la journée ?
Le matin à l'aube, lors de la confection des gâteaux pendant que Bordeaux est toujours endormie.

## Et enfin vos futurs projets ?
L'ouverture d'autres boutiques sur Bordeaux et sa région !

## Une marque à retrouver :
Dans notre box de Mars 2015 notamment
Sur facebook : <https://www.facebook.com/ChicCakes-467048806700574/timeline/>

EOD;
    return $string;

    }

    public function article31()
    {
        $string = <<<EOD

## Qui se cache derrière Bouches B. ?
Derrière Bouches B. se cache Bérénice, une jeune femme gourmande, aventureuse et passionnée de pâtisserie. Après différentes expériences dans l'Humanitaire, de multiples voyages et l'obtention du CAP Pâtissier, j'ai décidé de vivre pleinement de ma passion. J'ai donc créé ma propre société : Bouches B. pour réaliser des pâtisseries personnalisées. En effet, j'ai remarqué que pour chaque personne, et à chaque évènement particulier correspond une création pâtissière particulière. D'où mon envie de proposer des gâteaux 100% uniques, en fonction des saveurs préférées de chacun, à déguster pour les anniversaires, EVJF/G, Baby Shower, etc.

## Comment as-tu choisi ce nom ?
La pâtisserie, comme la cuisine, fait appel à tous les sens. Le visuel, la texture ou encore les saveurs d'un gâteau procurent un réel plaisir et souvent d'étonnantes surprises... à en rester "Bouche Bée" ! Je voulais donc y faire référence dans le nom de ma marque. "Bouches" au pluriel, puisque pour moi la pâtisserie est synonyme de partage et de convivialité. "B." fait référence à mon prénom Bérénice et à Bordeaux, ma ville de cœur qui m'a permis de concrétiser mon projet.

## Quel est ton produit préféré ?
En vrai fille que je suis, le chocolat bien sûr ! :P Mais aussi les fruits, que j'utilise de préférence de saison. Ces produits associés se révèlent d'ailleurs plein de saveur.

## Ton moment préféré de la journée ?
Sans hésitation, le Tea Time avec mes amis et ... des pâtisseries Bouches B. !

## Et enfin tes futurs projets ?
Je souhaite dans un futur proche me consacrer à 100% à Bouches B. et développer mon activité à Bordeaux et dans la CUB. Actuellement, je fais surtout des pâtisseries sur commande et pour la carte des desserts du coffee shop "Copine Claude", situé aux Chartrons. Je souhaiterai en plus m'orienter vers l'évènementiel (mariages, marchés des créateurs, vide dressing comme celui du 10 & 11 octobre 2015 au Garage Moderne...)

## Une marque à retrouver :
Dans la box de Juillet 2015 notamment
Sur facebook : <https://www.facebook.com/bouchesb?fref=ts>
Et bientôt dans notre e-shop ! ;)

EOD;
    return $string;

    }

    public function article30()
    {
        $string = <<<EOD

## Qui se cache derrière MPFK - Ma Petite Fabrik ?
L’idée c’était d’abord de partager mes coups de cœurs créatifs, mes petits bidouillages et ma passion pour la pâtisserie sur le blog de Ma Petite Fabrik… et de fil en aiguille, de maille en maille et de pliage en collage, j’ai eu envie de créer pour de vrai !
C'est ainsi que cette aventure est devenue la marque MPFK - Ma Petite Fabrik... , des accessoires pour les enfants et pour les mamans. De la baguette magique à la couronne de princesse, en passant par les guirlandes d’étoiles, les petites pochettes et de jolies corbeilles en trapilho. Crocheter de la fleur de coton naturel, c’est comme écouter un magnifique morceau de musique classique ; c’est poétique, doux et intense à la fois ! Au-delà de toutes ces créations, MPFK – Ma Petite Fabrik… c’est aussi des ateliers créatifs pour les adultes et les enfants, car ce que j'aime par dessus tout, c'est partager ma passion avec les autres…

## Comment as-tu choisi ce nom ?
D'aussi loin que je me souvienne, j'ai toujours adoré fabriquer des petites choses, pour les autres et pour moi, c'est donc assez naturellement que j'ai rangé toutes mes petites affaires dans cette Petite Fabrik...

## Quel est ton produit préféré ?
Mes produits préférés sont tous les petits accessoires de filles, qui permettent de rêver, comme les couronnes, les étoiles et les baguettes magiques ; mais aussi tout ce qui me permet de ranger mes petits trésors, les pochettes et les petites corbeilles ! :)

## Ton moment préféré de la journée ?
J'adore la nuit, quand tout le monde dort, que le calme est là et que les étoiles brillent...

## Et enfin tes futurs projets ?
Continuer à faire grandir cette Petite Fabrik à bonheur et pArtâger avec le plus grand nombre mes idées créatives dans des ateliers ! :)

## Une marque à retrouver :
Dans la box d'Avril 2015 notamment
Sur facebook : <https://www.facebook.com/MaPetiteFabrik?fref=ts>
Et bientôt dans notre e-shop ! ;)

EOD;
    return $string;

    }

    public function article29()
    {
        $string = <<<EOD

## Qui se cache derrière Fil'harmony ?
Je m'appelle Elodie et je suis créatrice de bijoux fantaisie. Comme toutes les femmes, j'aime assortir mes bijoux à mes tenues. Mes créations sont plutôt colorées, souvent romantiques avec toujours une pointe de légèreté.
J'aime créer des pièces sur-mesure pour aller plus loin dans la création et que celles-ci soient uniques !

## Comment as-tu choisi ce nom ?
J'ai fait beaucoup de couture et ai commencé à faire des bijoux avec mes chutes de tissus (d'où le mot "Fil"). On retrouve toujours dans certaines pièce des galons, ruban et liberty qui apporte une touche plus chaleureuse au bijoux. Et "Harmony" car le but dans une création est de trouver le juste équilibre. Et Fil'harmony pour le petit jeu de mot ! ;)

## Quel est ton produit préféré ?
J'aime beaucoup les boucles gouttes déclinées dans toutes les couleurs et tous les styles pour chaque saison. Les sautoirs pour mettre en valeur nos tenues les plus basiques et les headband qui sont THE accessoire indispensable pour toutes les grandes occasions !

## Ton moment préféré de la journée ?
Le matin, quand j'arrive à l'atelier, prête pour une journée de création !

## Et enfin tes futurs projets ?
Travailler avec de nouveaux matériaux comme l'émail !

## Une marque à retrouver :
Dans la box de Juillet 2015 notamment
Sur facebook : <https://www.facebook.com/Filharmony-1398683363730234/timeline/>
Et bientôt dans notre e-shop ! ;)

EOD;
    return $string;

    }

    public function article28()
    {
        $string = <<<EOD

## Qui se cache derrière Bastor-Lamontagne ?
20 personnes (ouvriers, bureau). Nous sommes producteur de Sauternes à Preignac avec 56 ha.

## D'où vient ce nom ?
Le domaine de Bastor-Lamontagne est jusqu’en 1710 la propriété du roi de France, successeur en Guyenne du roi d’Angleterre dont les biens sont confisqués en 1453. Quand le Sieur Vincent de La Montaigne, conseiller au parlement de Bordeaux achète Bastor-Lamontagne en 1711, les activités du domaine affichent déjà une option viticole très marquée. Si les La Montaigne contribuèrent à accélérer le mouvement, le véritable démarrage viticole de Bastor-Lamontagne sera l’œuvre de la famille Larrieu dont le règne sur le château durera jusqu’en 1904. En 1936, les descendants de la famille Larrieu vendent le domaine, propriété ensuite de la BPCE puis depuis Juillet 2014 des Galeries Lafayette.

## Quel votre produit préféré ?
Le Caprice de Bastor Lamontagne, notre nouveau crû, assemblé à partir de 50% de sémillon et 50% de sauvignon et créé notamment pour une clientèle plus jeune !

## Votre moment préféré ?
En ce moment ce sont les vendanges, l'arrivée des camions de raisin fraîchement récolté est un moment émouvant, la consécration d'une année de travail !

## Et enfin vos futurs projets ?
Rajeunir l'image du Sauternes, populariser sa consommation qui devient trop rare et le faire redécouvrir sous d'autres formes grâce à des associations de goût imprévues (avec du Perrier, du wasabi,etc.) !

## Une marque à retrouver :
Dans la box de Décembre 2014, Février et Avril 2015 notamment
Sur facebook : <https://www.facebook.com/bastorlamontagne?fref=ts>
Et bientôt dans notre e-shop ! ;)

EOD;
    return $string;

    }

    public function article27()
    {
        $string = <<<EOD

## Qui se cache derrière les Pie-Plettes ?
Nous sommes deux jeunes ingénieures dans l'agroalimentaire, gourmandes et bonnes vivantes. Nous aimons partager un bon repas ou un apéritif convivial avec notre entourage, c'est pour cela que nous nous sommes lancées dans les produits alimentaires.
Nous avons toutes les deux eu des expériences professionnelles différentes, ce qui nous rend très complémentaires malgré notre formation similaire... et puis nous avons des caractères très différents, ce qui nous permet de rebondir dans toutes les situations !
Nous avons voulu créer une entreprise à notre image : dynamique et avec de bons produits, plus sains mais savoureux, pour des moments de partage sans prise de tête.

## Comment avez-vous choisi ce nom ?
Nous proposons des produits sans gluten et plus "sains" mais nous ne voulions pas nous enfermer dans un nom qui fasse trop santé et qui mette de côté le plus important, le plaisir et la rigolade qu'on peut retrouver au cours des repas !
Les Pie-Plettes nous représentent bien car nous sommes deux filles qui aiment beaucoup parler et rigoler et puis le jeu sur la "pie" fait allusion à l'oiseau qui trie sa nourriture, qui préfère les bonnes choses et qui n'hésite pas à piquer dans l'assiette des autres, ce que l'on fait aussi à l'apéritif non ! ;D

## Quel votre produit préféré ?
Nous proposons actuellement 4 recettes de crackers apéritifs et étendrons ensuite la gamme.
Les préférés sont :
le Lupi Crack : avec son goût qui rappelle la pizza un peu épicée il fait un ravage auprès des petits comme des grands
le Cracki Cheese : à l'emmental c'est le "crackers traditionnel", celui que tout le monde aime
et enfin le Cracki Chèvre : il est original et permet de sortir un peu des goûts habituels... Et comme ils ne contiennent pas d'exhausteur de gout ni d'arômes artificiels, ils sont fait avec de vraies tomates et du bon fromage de Hollande, impossible d'y résister !

## Votre moment préféré de la journée ?
Nous aimons quand les clients nous demandent des idées recettes dans lesquelles utiliser nos crackers, c'est convivial, on se creuse les méninges et on rigole avec eux ! :)

## Et enfin vos futurs projets ?
Nous voulons acheter un parc de machines afin de pouvoir produire de façon plus importante et commercialiser nos produits à plus grande échelle, pour que tous les intolérants au gluten puissent goûter nos produits !
Ensuite, nous développerons aussi de nouveaux produits, en fonction des besoins, des envies et des attentes de nos consommateurs, nous voulons construire cette entreprise ensemble. :)

## Une marque à retrouver :
Dans la box de Septembre 2015 notamment
Sur facebook : <https://www.facebook.com/Les-Pie-Plettes-597170663745628/timeline/>
Et bientôt dans notre e-shop ! ;)

EOD;
    return $string;

    }

    public function article26()
    {
        $string = <<<EOD

## Qui se cache derrière ta marque ?
Je m'appelle Émilie, j'ai 33 ans et je suis née en Normandie.
J'ai fait mes études d'arts appliqués à Paris. En 2009, pour changer et quitter la grisaille parisienne, je viens vivre à Bordeaux et décide de créer ma marque de bijoux et accessoires. Fin 2013, j'ouvre ma boutique-atelier, dans le quartier St Pierre, avec la créatrice Lalabulle !

## Comment as-tu choisi ce nom ?
Ma marque, c'est mon nom car dans chacune de mes créations, il y a un bout de moi !

## Quel est ton produit préféré ?
En premier, je pense à mes boucles d'oreille pendantes simples ; de 7 à 77 ans, elles plaisent à tout le monde !
En deuxième, les bracelets, légers et raffinés, ils sont faciles à porter à tous les jours.
En dernier, je dirais les boucles d'oreille chaînes pendantes, elles apportent beaucoup de classe à toutes les tenues.

## Ton moment préféré de la journée ?
Le matin, quand j'arrive à l'atelier, prête pour une journée de création !

## Et enfin tes futurs projets ?
Mon objectif, pour début 2016, vendre partout en France par le biais de boutiques indépendantes ou de créateurs !

## Une marque à retrouver :
Dans la box de Septembre 2015 notamment
Sur facebook : <https://www.facebook.com/Emilie-Dubot-Bijoux-329033722281/timeline/>
Et bientôt dans notre e-shop ! ;)

EOD;
    return $string;

    }

    public function article25()
    {
        $string = <<<EOD

## Qui se cache derrière la Manufacture de Soda ?  
François Delmas Saint Hilaire et Marion Gaudicheau ! Diplômés depuis trois ans d’une école d’agronomie, nous avons toujours eu le souhait de créer un jour notre entreprise. Nous avons cherché à développer un produit original avec un approvisionnement en matières premières local. Fan de sodas depuis notre enfance, nos goûts ont évolué et se sont affinés.
Il existe une réelle demande pour des boissons gazeuses fruitées peu sucrées et peu caloriques. Nous avons découvert qu’il n’existait pas de sodas 100% naturels à base de fruits locaux. C’est pour cela que nous avons créé en Février 2015 La Manufacture de soda à Bordeaux.
Nous avions deux ambitions pour ce produit innovant :
Fabriquer un soda de qualité pour donner une image positive et haut de gamme à ce produit souvent décrié par les nutritionnistes
Communiquer en toute transparence sur le producteur et l’origine des fruits !

## Comment vous avez choisi ce nom ?
Nous voulions réutiliser le mot Manufacture qui derrière son image de grande usine signifie "fait main", un peu comme nos sodas ! :D

## Quel est ton produit préféré ?
Pour savoir lequel de nos sodas vous préférez, il suffit de tous les goûter ! ;D
Pour nous, le soda Raisin qui sort tout juste de notre Manufacture est une réussite (et c'est pas peu dire à Bordeaux dans le monde du vin !) !

## Votre moment préféré de la journée ?
Le matin quand on part travailler à La Manufacture... Un bonheur de se dire que l'on va bosser dans NOTRE entreprise ! :)

## Et enfin vos futurs projets ?
Elargir la gamme de nos sodas pour proposer une gamme diversifiée toute l'année. Envisager de la vente ambulante sur les quais l'été. Trouver de nouveaux clients sur le Bassin d'Arcachon ! Plein de choses !

## Une marque à retrouver :
Dans la box de Juillet 2015 notamment
Sur facebook : <https://www.facebook.com/Manufacture-de-Soda-33-776237885759315/timeline/>
Et bientôt dans notre e-shop ! ;)

EOD;
    return $string;

    }

    public function article24()
    {
        $string = <<<EOD

## Qui se cache derrière la Conserverie de la Tour ?  
Je m'appelle Lorée, j'ai tout juste 28 ans. Passionnée d'agriculture, d'horticulture, de maraîchage, j'ai un BTS horticole que j'ai effectué à l'ESA d'Angers. J'ai toujours aimé cuisiné dans les jupes de ma grand mère, alors les conserves ça me connait !
Plus sérieusement, consciente de l'impact écologique qu'ont nos cultures intensives, il fallait réagir. Quoi de mieux que de décaler les saisons dans son assiette avec des produits locaux et cultivés en agriculture raisonnée ? Je combine ainsi mes deux passions, cuisine et nature.

## Comment tu as choisi ce nom ?
La Conserverie de la Tour doit son nom à une tour tout simplement. En effet, le local est situé à Cadillac, rue de la Tour ! Cadillac était une ville fortifiée avec des remparts et des tours. Il est possible d'accéder à la Tour de Branne seulement en passant par la Conserverie, les constructions, au fil des années l'ont encerclée. Un joyaux du 13 ème siècle tout au fond de la Conserverie. Il sera certainement possible de la visiter après quelques aménagements et les filles de Bordeaux in Box seront sans doute parmi les premières à venir visiter ! ;)

## Quels sont tes 3 produits préférés ?
Mes produits préférés dépendent souvent de la saison. Le gaspacho rouge, frais pour l'été est un délice mais il va falloir attendre l'année prochaine... La soupe de potimarron, j'adore, quand il fait bien froid, après une dure journée, c'est mon petit réconfort ! :P
En toutes saisons, la confiture poire/verveine est ma préférée, suivie de très près par l'abricot/amandes effilées.

## Ton moment préféré de la journée ?
Coller mes étiquettes ! :D C'est tout bête mais c'est le moment où le produit est vraiment fini et qu'il est prêt à partir vers de nouveaux horizons et de nouveaux palais !

## Et enfin tes futurs projets ?
Créer la boutique attenante à la conserverie et faire visiter les locaux aux curieux... avec peut être une Tour mystérieuse au bout du chemin... ;)

## Une marque à retrouver :
Dans la box de Septembre 2015
Sur facebook : <https://www.facebook.com/Conserverie-de-la-Tour-880963618623387/timeline/>
Et bientôt dans notre e-shop ! ;)

EOD;
    return $string;

    }

    public function article23()
    {
        $string = <<<EOD

## Les bijoux de la marque CIELL se distinguent par leur forte identité féminine, naturelle aux formes géométriques et colorés, mais qui sont les  petites mains qui se cachent derrière ces parures qui nous enchantent ?  

Gwenaëlle, dijonnaise mais bordelais d'adoption puise ses références dans son parcours lié aux arts et notamment à ceux du théâtre.

Laetitia, bordelaise, s'inspire de sa formation dans les arts plastiques pour nourrir sa pratique. 

C'est en 2013, quelques années après leur rencontre, qu'elles décident de créer FUGU. En avril 2014 leur activité se professionnalise et FUGU devient CIELL !

## Et justement, pour quelles raisons avoir choisi le nom CIELL ?

Les filles choisissent d'adopter un nouveau nom correspondant d'avantage à leur univers, elles décident de se rapprocher du champs lexical de la nature et de la féminité, 2 points essentiel dans leur créations qu'elles souhaitent mettre en avant.

## Quelles sont les particularités des bijoux de CIELL ?

Les créatrices de CIELL s'efforcent de donner une véritable identité à leur marque, elles se distinguent particulièrement en faisant du cuir le matériau principal de leurs collections; elles maîtrisent très justement l'assemblage de multiples couleurs, épaisseurs et textures de cette matière qu'elles associent aussi parfois à d'autres matières naturelles dignes de leur univers, telles que des plumes ou du coton, afin de créer des formes à leurs pendentifs toujours très féminines, sensibles et naturelles telles que des petits nuages, attrapes-rêves, etc..

Inutile de préciser que leurs créations sont faites exclusivement à la main en petites séries que Gwenaëlle et Laetitia renouvellent sans cesse, au fil des saison et des modes en proposant régulièrement de nouvelles collections.

## Où trouve-t-on leurs créations ?

Les filles possèdent une petite boutique en ligne à travers le site Etsy : <https://www.etsy.com/fr/search?q=ciellcreation>

Leurs création et actualité est aussi disponible sur facebook : <https://www.facebook.com/ciellcreation?fref=ts>

Et leur actualité est à suivre de près car les filles n'arrêtent pas de bouger, vous les trouverez régulièrement dans différents évènements bordelais, marchés, salons, mais aussi dans quelques autres grandes villes de France. 


EOD;
    return $string;

    }

    public function article22()
    {
        $string = <<<EOD

## Qui se cache derrière cette boutique de bien être et cosmétiques bio ?

Maritza a 33 ans et c'est une grande passionnée de nature, de sport, d'écologie... En somme, de bien être de la terre et de soi !

Avant d'atterrir sa jolie boutique à Bordeaux, Maritza a fait plusieurs choses ! Diplômée d'un master en école de chimie spécialisée dans les produits de santé, ainsi que d'un master en marketing elle a eu plusieurs expériences professionnelles, notamment responsable marketing de plusieurs marques de cosmétiques bio pendant 4 ans en Suisse, jusqu'à ce qu'elle se décide à ouvrir son propre commerce de produits bio afin d'être totalement en accord avec ses valeurs.

## Comment a-t-elle choisi le nom de sa boutique ?

Ouverte depuis plus d'1 an, sa boutique est en effet en accord avec ses convictions ; engagée, sensible au bien être, à la nature, il lui fallait donc trouver un nom qui lui ressemble. C'est naturellement que lui est venue l'idée de nommer sa boutique par son joli prénom !


## Quels sont ses 3 produits préférés de sa boutique ?

Tout d'abord "Buddy Scrub" un gommage géniallissime 100% naturel à l'huile de coco, d'amande douce, au sel de mer et au sucre roux ! (ça donne presque envie de le manger mais ça n'aura sans doute pas le même effet !)

Le masque pour le visage éclat d'Alorée aux pépins de raisin et chlorophylle, oxygénant et détoxifiant.

Et la crème de jour d'Absolution avec un superbe packaging qui réunit une multitude d'ingrédients bio - ce qui est rare d'en concentrer autant ! - procurant d'énormes bienfaits pour la peau.



## Son moment préféré de la journée :

Partager avec ses clients, ses conseils et astuces beauté et bien-être. Maritza est tellement passionnée qu'elle a une véritable "soif" de donner des informations, de contribuer à un mouvement positif et sain.



## Quels sont ses futurs projets ?

Développer sa boutique en ligne : www.maritza.fr pour pouvoir satisfaire et toucher des clientes même à distance car il est possible de se faire livrer n'importe quel produit de la boutique directement chez soi !



Et dans un futur un peu plus lointain (mais certain !) la prochaine étape serait d'ouvrir un espace de beauté totalement bio et éco avec des protocoles de soins et de bien être... A suivre avec attention !
EOD;
    return $string;

    }

    public function article19()
    {
        $string = <<<EOD


## Lou Poppin's qu'on connaissait pour ses bijoux, accessoires et textiles fantaisie s'est transformée en friperie. Mais d'abord, qui se cache derrière cette petite boutique ?

Lisa-Annabelle, j'ai  24 ans, je suis une grande cinéphile, j'aime aller chiner, autant des vêtements que des objets et des meubles. Vous risquez de rencontrer aussi derrière le comptoir ma jeune chienne Jude qui me suit partout !


## Quel est ton cheminement jusqu'à l'ouverture de ta friperie ?

Suite à ma formation de couture, je me suis lancée dans la création de ma collection de textile pour enfants. J'ai eu l'opportunité d'avoir un local sympa, tout petit mais très chouette pour me lancer, ce que j'ai fait en Septembre dernier. Malheureusement ça n'a pas été facile, la concurrence étant trop importante dans le milieu de l'enfant, et mon affaire peut-être pas adaptée aux besoins du quartier.  Au bout de six mois, je n'avais donc plus le choix entre vendre ou créer un nouveau concept.

Pour rester fidèle à mes centres d'intérêt c'est une friperie que j'ai décidé d'ouvrir ! Cela fait un mois que j'ai ouvert et je suis très contente, les prix sont volontairement abordables, car le but est que le stock change sans cesse et qu'à chaque fois que vous veniez, vous découvriez de nouvelles choses. On y trouve autant du vintage, que du H&M ou du Balenciaga ! 

Et la nouveauté avec mon nouveau concept, c'est que ma boutique n'est pas seulement destinée aux filles !' J'ai choisi de faire un petit rayon homme, vous pouvez y trouver quelques vêtements car la boutique n'est pas grande (c'est la plus petite de la rue Notre Dame !) on s'adapte sur place pour les essayages etc… C'est à la bonne franquette ! Hihi, du coup ça crée une atmosphère plutôt intime et je trouve ça chouette !



## Comment as tu choisi le nom de ta boutique ?

Le nom de la boutique était surtout en rapport avec le monde de l'enfant dans lequel je m'étais lancé au départ.  Je faisais beaucoup de gardes d'enfants et on m'appelait toujours Mary Poppin's,  et comme je suis une fétichiste des loups en plus,  j'avais donc choisi LouPoppin's ! Pour le moment j'ai décidé de garder le nom car il fait partie de mon histoire et il me correspond plutôt bien ! (ma boutique c'est un peu comme dans le sac de Mary Poppin's, il y a tout un tas de choses à découvrir !)



## Quels sont tes futurs projets, tes rêves ?


Mon projet pour le moment est de bien développer la friperie, pouvoir racheter et revendre afin d'avoir un stock qui change constamment, c'est déjà c'est un beau rêve qui se réalise d'être parvenue à tout ça.

Du coup je ne pense pas vraiment aux rêves de demain, j'essaie déjà de faire vivre celui d'hier et d'aujourd'hui !
EOD;
    return $string;

    }

}
