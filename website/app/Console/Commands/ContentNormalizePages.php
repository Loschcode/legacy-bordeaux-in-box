<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Page;

class ContentNormalizePages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:normalize-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Normalize pages with the markdown format';

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

        $this->deletePage(1, 'Home');
        $this->deletePage(2, 'Contact');
        $this->deletePage(5, 'Bill');

        $ids = [3, 4, 6, 7, 8];

        foreach ($ids as $id) {
            
            $page = Page::find($id);

            if ($page !== NULL)
            {
                $page->content = $this->{'page' . $page->id}();
                $page->save(); 
                $this->info($page->title . ' updated !');
            }
        }
    }

    public function deletePage($id, $page_name)
    {
        // Delete Page contact
        $this->info('Trying to delete page ' . $page_name);

        if (Page::find($id) !== NULL) {

            Page::find($id)->delete();
            $this->line('Success !');

        } else {
            $this->error('Can\'t find page ' . $page_name);
        }
    }

    public function page3()
    {
        $string = <<<EOD

**Bordeaux in Box** est un site édité par **La Petite Box SAS**

**Siège social:** 18 avenue Gustave Eiffel, 33600 Pessac

**Président:** Laurent Schaffner

**Directeurs:** Hugo Bazin, Jérémie Ges

**Illustratrice:** Lola Goulard

Pour nous contacter, c'est par ici: [nous contacter](http://www.bordeauxinbox.fr/contact)

EOD;
        return $string;

    }

    public function page4()
    {
        $string = <<<EOD
https://www.facebook.com/BordeauxinBox
EOD;
        return $string;

    }

    public function page6()
    {
        $string = <<<EOD
18 avenue Gustave Eiffel, 33600 Pessac
EOD;
        return $string;

    }

    public function page7()
    {
        $string = <<<EOD
## Que vais-je trouver dans la box ?

Des petites marques locales, des créateurs, des artisans de Gironde ... Cela dépend des mois et des envies de l'équipe ! Chaque mois est différent donc il y aura toujours quelque chose pour te plaire dedans. Pour te donner quelques exemples, on y a déjà glissé du vin, des bijoux, du parfum, des cosmétiques, des cartes cadeaux pour des séances photo, des petites plantes, des préparations pour canelés, des places de concert, des dessins d'artistes ou encore des jouets pour enfants (pour les Mamounes) !

Si tu es très très curieuse, voici le lien vers nos albums photo facebook où nous réunissons les photos prises par vous !



## Comment fonctionnent les thèmes ?

Chaque thème correspond à un style dominant qu'on met dans ta box mais toutes reçoivent au moins un bijou de créateur et une petite bouteille de vin ainsi que deux autres produits. Ces thèmes ne veulent pas dire que tu recevras chaque mois un produit lié à ce thème mais influencent notre manière de concevoir la box et comment nos partenaires travaillent. Et dès qu'un produit correspond ou que l'on a une idée, cela nous permet de savoir à qui les donner !
Ainsi, les Princesses auront plus souvent des choses pour se faire belles ou décorer leur petit chez-soi. Les Mamounes, des doudous pour enfants, des petites choses pour la maison ou encore des choses pour se faire du bien et les Poulettes des dessins ou autres créations d'artiste, des places de théâtre ou même des dégustations de bière ! :)



## Combien coûte-t-elle ?

La box coûte actuellement 24,90€ en abonnement, si tu hésites, tu peux tester notre box pour 26,90€ sans abonnement.



## Quand est-elle envoyée ?

La date d'envoi est indiqué sur le site dans l'espace client (Mon compte -> Abonnements -> Détails) une fois la commande faite mais en général, elle arrive autour du 10 du mois donc si tu tardes trop à la commander, il faudra attendre la suivante ! ;)



## Si je n'habite pas sur Bordeaux, est-ce handicapant ?

Non ! Pas du tout ! Dans la box, on fait toujours attention à mettre beaucoup de produits directement utilisables. Alors bien sûr, nous ne te glisserons pas les invitations aux soirées ou aux évènements que l'on organise à Bordeaux si tu habites trop loin, mais nous les remplacerons par un petit quelque chose en plus ! :)


## Quelles sont les règles et conditions d'achat de ma box ?

Lors de l'achat de ta box, tu acceptes nos [conditions générales de vente](http://www.bordeauxinbox.fr/cgv). Celles-ci sont plutôt classiques et flexibles donc pas d'inquiétude ;)
EOD;
        return $string;

    }

    public function page8()
    {
        $string = <<<EOD
## Préambule

Les présentes Conditions Générales de Vente régissent exclusivement l'activité de diffusion de produits par le biais de l'envoi d'une box dans le but de permettre aux fabricants de promouvoir l'identité de leurs produits réservée aux personnes ayant conclu un contrat d'abonnement (ci-après « le Service d'Envoi de Box »), ainsi que l'activité de vente au détail Box spéciales, accessible à toute personne sans abonnement (ci-après « le Service de Vente au Détail de Box spéciales »).

Pour l'application des présentes, il est convenu que les personnes ayant conclu un contrat d'abonnement et/ou validé une commande de vente au détail de Box spéciales seront alors dénommés « Clients ».

Ces services sont disponibles sur le site internet « bordeauxinbox.fr » (ci-après « le Site »). Les présentes Conditions Générales de Vente sont systématiquement accessibles sur le présent Site par les Clients au moment de l'enregistrement de la commande et/ou la souscription à un abonnement.

Régies par l'article L. 441-6 du code de commerce et conformément à l'article L.111-1 du code de la consommation, les présentes Conditions Générales de Vente déterminent les droits et obligations de La Petite Box SAS (ci-après « la Société ») et des Clients (ensemble « les Parties », individuellement « la Partie ») aux différents contrats proposés sur le Site.

Par le seul fait de valider sa commande sur le Site, le Client déclare avoir lu, compris et accepté sans réserves les termes de ladite commande ainsi que l'intégralité des présentes Conditions Générales de Vente.

Les Conditions Générales de Vente ne sont valables qu'en langue française.

Les présentes Conditions Générales de Vente sont applicables dans leurs termes au jour de la souscription selon leur rédaction sur présente sur le Site. Elles pourront cependant faire l'objet de modifications.



## Article 1 - Présentation de la Société

Le Site « bordeauxinbox.fr » est édité par la société par actions simplifiées La Petite Box SAS au capital social de 600 euros dont le siège social est situé 18 avenue Gustave Eiffel à Pessac (33600), inscrite au registre du Commerce et des Sociétés de Bordeaux sous le numéro 811 767 532.

 

## Article 2 - Capacité juridique

Les Clients déclarent être majeurs et pleinement capables de contracter.

 

## Article 3 - Zone géographique

Les produits et services sont proposés uniquement en France métropolitaine. Les abonnements ne répondant pas à cette condition géographique ne pourront être pris en considération.

 

## Article 4 - Accès au service

Le services du Site sont normalement accessibles aux Clients 7 jours sur 7, 24 heures sur 24 toute l'année sauf en cas d'interruption volontaire ou non, notamment pour des besoins de maintenance ou de force majeure. La Société étant de fait, par son activité, tenue à une obligation de moyen, elle ne pourra être tenue responsable de tout préjudice quelle qu'en soit la nature, résultant d'une indisponibilité du Site.

 

## Article 5 - Conditions d'abonnement au service d'envoi de box

Le service d'envoi de box consiste en l'expédition chaque mois calendaire d'une « box » comprenant notamment des produits et échantillons de produits.

La souscription à l'abonnement au service d'envoi de box proposée par la Société s'effectue par le Site. La souscription peut éventuellement s'effectuer à travers d'autres supports sur décision de la Société.

Par la souscription d'une des formules d'abonnement au service d'envoi de box, le Client accepte les présentes Conditions Générales de Vente, dont le Client reconnait avoir pris connaissance, les avoir comprises et les accepter sans réserve et en toute connaissance de cause.

Dans un souci de bonne administration de la souscription par un Client à un des abonnements au service d'envoi de box, le Client devra remplir un formulaire comprenant ses données personnelles. Les renseignements fournis à la Société doivent impérativement être exacts. Le Client doit veiller à leur justesse et à leur conformité lors de la souscription. En cas de communication de données erronées, la Client pourra voir sa responsabilité engagée.

La Société propose quatre modalités d'abonnement au service d'envoi de box : soit un abonnement mensuel pour soi-même avec paiement chaque mois (5.1), soit un achat d'une box avec paiement unique (5.2), soit un abonnement limité dans le temps avec un paiement chaque mois (5.3), soit un abonnement mensuel à offrir avec paiement unique (5.4).

 

### 5.1. L'abonnement mensuel pour soi-même avec paiement chaque mois.

La souscription à l'abonnement mensuel abonne le souscripteur au service d'envoi mensuel d'une box jusqu'à ce qu'une Partie décide de mettre fin à ce contrat. Par l'acceptation de cette offre le Client accepte un prélèvement mensuel d'un montant défini lors de la commande initiale. Ainsi, tant que perdure l'abonnement le Client sera prélevé de la somme définie automatiquement chaque mois et le Client recevra la box entre le 5 et le 25 du mois au plus tard.

L'Abonné peut mettre un terme à son abonnement mensuel à tout moment selon les modalités prévues à l'article relatif au désabonnement des présentes Conditions Générales de Ventes.



### 5.2. L'achat d'une box avec paiement unique

La souscription à l'achat d'une box avec paiement unique permet d'abonner soi-même ou un tiers pour une période d'un mois Le paiement de cet abonnement sera effectué en une seule fois, lors de la souscription



### 5.3. L'abonnement limité dans le temps avec un paiement chaque mois

La souscription à l'abonnement semestriel permet de s'abonner soi-même pour une période défini lors de la souscription. Le paiement de cet abonnement sera effectué chaque mois. Le premier paiement sera effectué lors de la souscription.



### 5.4. L'abonnement mensuel à offrir avec paiement unique

La souscription à l'abonnement mensuel à offrir avec paiement unique permet d'abonner soi-même ou un tiers pour une période définie lors de la souscription. Le paiement de cet abonnement sera effectué en une seule fois, lors de la souscription.

 

## Article 6 - Conditions de vente ponctuelle au détail de Box spéciales.

La Société propose également par le biais du Site un service ponctuel de vente au détail de Box spéciales. Les produits proposés à la vente sont ceux qui figurent sur le Site, au jour de la consultation du Site par l'utilisateur.

Les produits sont proposés dans la limite des stocks disponibles. En cas d'indisponibilité de l'un des produits, le Client en sera informé au plus tôt par une mention sur le Site.

 

## Article 7 - Envoi d'emails

En acceptant les présentes Conditions Générales de Vente lors de la souscription d'une des formules d'abonnement ou d'une commande sur le Site, le Client autorise la Société à lui envoyer des emails d'information à l'adresse qu'il aura renseignée lors de son abonnement ou de sa commande.

 

## Article 8 - Désabonnement

Seuls les Clients bénéficiant d'un abonnement mensuel peuvent se désabonner. Le Client peut à tout moment mettre un terme à son abonnement mensuel en contactant le support technique (section "Contact")

Si la résiliation intervient après le prélèvement automatique effectué chaque mois le jour fixée lors du premier paiement, ou après que la mise en place de la préparation des commandes de la série ait été prise par la Société (statut "En préparation" pour la commande), la commande est réputée conclue pour le mois concerné, l'abonné ne pourra exiger l'annulation de cette commande en arguant de sa demande de résiliation. Le désabonnement est pris en compte pour le mois suivant.

En cas de désabonnement, un client bénéficiant d'un abonnement à un prix inférieur dû à son ancienneté, perdra ce privilège et devra payer le tarif en vigueur s'il souhaite se réabonner.

Le désabonnement peut également résulter de la décision de la Société suivant les modalités prévues à l'article relatif à la désactivation de compte client.

 

## Article 9 - Désactivation de compte client

En cas de non-respect des obligations découlant de l'acceptation des présentes Conditions Générales de Vente, d'incidents de paiement du prix d'une commande, de délivrance d'informations erronées à la création du compte ou d'actes susceptibles de nuire aux intérêts de la Société, la Société se réserve le droit de suspendre l'accès aux services proposés sur le Site ou, en fonction de la gravité des actes, de résilier l'abonnement et le compte du Membre sans que des dommages et intérêts puissent être réclamés.

La société se réserve également le droit de refuser de contracter avec un Client ayant été exclu ou sanctionné pour de tels agissements.

 

## Article 10 - Tarifs et Paiement

 

### 10.1 Modalités générales de paiement pour tous les services du Site

Le paiement des services se fera par carte bancaire (Bleue, Visa, Mastercard). Les paiements effectués seront sécurisés par une procédure de cryptage des données en vue d'éviter l'interception de ces informations par un tiers.

La Société ne saurait être tenue responsable en cas d'usage frauduleux des moyens de paiement utilisés.

Dans un délai de trois jours suivant la réception de la demande d'abonnement une demande de débit du compte bancaire sera adressée à l'organisme payeur. Le contrat d'abonnement sera conclu à la réception de l'autorisation de débit du compte par l'organisme payeur.

Conformément à la réglementation en vigueur les coordonnées bancaires des Membres ne sont pas conservées par la Société.

 

### 10.2 Tarifs et paiement du service mensuel d'envoi de box

Les tarifs du service d'envoi mensuel des box sont ceux présentés sur le Site, toutes taxes comprises. Les tarifs peuvent être modifiés. Le cas échéant, les modifications ne valent que pour les commandes futures, les commandes déjà payées ne seront pas affectées par les modifications tarifaires.

 

### 10.3 Prix de vente et expédition pour la vente au détail de Box spéciales.

Les prix des produits proposés sont indiqués en euros. Ils tiennent compte de la TVA applicable au jour de la commande. Les  frais de livraison et sont précisés au Client lors de la validation définitive de sa commande.

Le client recevra un e-mail de confirmation du paiement à l'issu de la validation de la commande.

 

## Article 11 - Preuve

Le Client reconnaît la validité et la force probante des échanges et enregistrements électroniques conservés par la Société et admet que ces éléments reçoivent la même force probante qu'un écrit signé de manière manuscrite en vertu de la Loi n° 2000-230 du 13 mars 2000 portant adaptation du droit de la preuve aux technologies de l'information et relative à la signature électronique.

 

## Article 12 : Livraison



### 12.1 Livraison - Service d'envoi de Box

 

## Zones de livraison

Le service d'envoi de box couvre uniquement la zone géographique déterminée à l'Article « zone géographique », c'est-à-dire la France métropolitaine.

 

## Modalités de livraison

Les Box seront livrées aux Clients avant le dernier jour du mois à l'adresse ou au Point Relais renseigné dans le formulaire de renseignement rempli par le Client lors de l'inscription. En cas de changement d'adresse ou de Point Relais, il appartient au Client de le notifier avant que la mise en place de la préparation des commandes de la série ait été prise par la Société (statut "En préparation" pour la commande), en modifiant ses coordonnées sur le formulaire de renseignement dans la section « Mon compte ».

Dans le cas où le colis serait renvoyé à la Société, une seconde livraison ou un remboursement sera effectué sur décision de la Société.

 

## Moyens de livraison

Le mode de livraison est choisi par le client au moment de la souscription de l'abonnement. La modification du mode de livraison en cours d'abonnement est possible pour les abonnements mensuels seulement, depuis l'espace « Mon Compte » ou sur demande à la Société. Si le changement est effectué après que la mise en place de la préparation des commandes ait été prise par la Société (statut "En préparation" pour la commande), il ne prendra son effet que le mois d'après. Le montant prélevé mensuellement sera ainsi automatiquement recalculé en fonction du mode de livraison sélectionné, et de son tarif.

La Société informe ses Clients qu'elle ne peut garantir les délais de livraison, ceux-ci étant géré par des organismes extérieur, les délais affichés sur le site sont approximatifs.



### 12.2. Livraison - Service de Vente au Détail

Le service de vente au détail des Box spéciales couvre uniquement la zone géographique déterminée à l'Article «zone géographique», c'est-à-dire la France métropolitaine.

La Société ne pourra voir sa responsabilité engagée pour les retards de livraison et les conséquences qui pourraient en découler.



## Article 13 - Remboursement

Toutes réserves sur la box et/ou la commande et son contenu doivent être notifiées dans un délai de trois jours à compter de la réception de la box. La défectuosité de la box et/ou la commande avérée, le Client pourra obtenir une nouvelle box et/ou commande dans la limite des stocks disponibles. Si la box n'est plus disponible en stock, le Client pourra obtenir la box du mois suivant gratuitement. Si les produits de la commande ne sont plus en stocks, la Société s'engage à rembourser le montant de la commande.

L'absence ou la défectuosité d'un produit contenu dans la box et/ou la commande donnera lieu à l'échange d'un même produit selon les stocks disponibles ou d'un autre produit.

L'absence de réception d'une box et/ou la commande ne saurait donner lieu à d'autres indemnisations. En tout état de cause, la responsabilité de la Société est limitée par la valeur mensuelle de l'abonnement et de la commande.

Pour obtenir le remboursement dans ces conditions, le Client devra impérativement notifier son choix d'être remboursé dans un délai de trente (30) jours.

 

## Article 14 - Force majeure

La Société ne pourra être considérée comme engageant sa responsabilité pour inexécution contractuelle totale ou partielle qui aurait pour cause un événement de force majeure, indépendant de notre volonté.

 

## Article 15 - Conformité des produits

Les informations présentes sur le Site Internet relatives aux produits sont celles fournies par les fabricants et fournisseurs. La Société ne pourrait donc en aucun cas voir sa responsabilité engagée pour les conséquences pouvant découler de la connaissance ou de l'utilisation de ces renseignements.

 

## Article 16 - Propriété intellectuelle

Tous les éléments du Site, qu'ils soient visuels ou sonores, les textes, mises en page, illustrations, photographies, documents et autres éléments, y compris la technologie sous-jacente, sont protégés par le droit d'auteur, des marques et des brevets. Toute reproduction totale ou partielle des éléments accessibles sur le Site est strictement interdite.

 

## Article 17 - Faculté de rétractation

Conformément aux textes applicables en vigueur du code de la consommation et dans le cadre de la vente à distance, le Client dispose d'un délai de rétractation de quatorze (14) jours francs à compter de l'acceptation de la souscription à une offre d'abonnement et/ou de la validation d'une commande. La notification de rétractation devra être faite par courrier ou par mail (via la page "Contact" sur bordeauxinbox.fr) à La Petite Box à l'adresse mentionnée à l'article 1er des présentes Conditions Générales de Vente.

Le délai de quatorze (14) jours est également applicable en cas de rétractation suite à la réception de la box et/ou de la commande. Ce délai court à compter de la réception de la box mensuelle et/ou de la commande. Les marchandises doivent être impérativement retournées dans leur conditionnement et emballage initial. Tout produit incomplet, abimé, endommagé et/ou l'emballage aura été détérioré ne sera ni repris, ni échangé, ni remboursé. En effet, l'abonnement portant sur une box complète par mois, le retour de la box complète est impératif.

Le cas échéant, la Société remboursera le Client ayant notifié dans le délai l'exercice de son droit de rétractation dans un délai de trente (30) jours au maximum à compter de la réception de la notification de rétractation. Le compte du Client sera recrédité de la somme débitée.

 

## Article 18 - Informations et libertés

Les informations demandées au Client dans le questionnaire au moment de l'inscription sont nécessaires au bon traitement de sa commande et pourront être communiquées aux fournisseurs partenaires contractuels du Site intervenant dans le cadre de l'exécution de cette commande.

Conformément à la loi applicable en vigueur le Client dispose des droits d'opposition, de droits d'accès et de droits de rectification des données le concernant. Le Client peut exiger que soient modifiés, complétés, clarifiés ou effacés les renseignements le concernant qui sont erronés, périmés ou incomplets ou dont la collecte ou l'utilisation, la communication sont interdites. Pour faire valoir ce droit il suffit à l'Abonné de nous écrire sur notre page internet « Contact ».

Nous nous réservons le droit d'utiliser les statistiques fournies par les formulaires de renseignement que les Abonnés auront complétés dans le but d'optimiser notre service et celui de nos partenaires.

 

## Article 19 - Responsabilité

La Société en sa qualité de tiers ne saurait voir sa responsabilité recherchée du fait du contenu des sites partenaires de La Petite Box, au même titre sa responsabilité ne pourra aucunement être recherchée en cas de conflit entre le Client et un site ou une marque partenaire.

Les informations permettant au Client de s'identifier, telles que l'identifiant et le mot de passe, sont personnelles et confidentielles. Ces informations ne peuvent faire l'objet de modification que sur initiative du Client ou de bordeauxinbox.fr notamment en cas d'oubli du mot de passe.

Le Client est seul responsable de l'utilisation de ses éléments d'identification, il est tenu de les garder secrets. Toute divulgation de sa part ne saurait en aucun cas être reprochée à bordeauxinbox.fr.

Toute commande effectuée à l'aide des identifiant et mot de passe du Client est réputée être passée par ce dernier. Bordeauxinbox.fr ne saurait en aucun cas être tenu responsable des dommages occasionnés par la divulgation de ces données personnelles et confidentielles par l'Abonné et donc de l'utilisation de ces données par un tiers.

La Société ne pourra en tout état de cause voir sa responsabilité recherchée pour tout dommage de quelque nature qu'il soit, notamment du fait de l'utilisation des services souscrit, une atteinte à la réputation, à l'image, ou une perte de données qui pourraient survenir du fait de l'utilisation des services proposés par bordeauxinbox.fr.

 

## Article 20 - Clause de sauvegarde

Si une ou plusieurs stipulations des présentes conditions générales de vente étaient déclarées non valides en application d'une loi ou réglementation ou d'une décision de justice définitive, les autres stipulations garderaient force et portée.

 

## Article 21 - Transfert des droits et obligations

En cas de cession totale ou partielle de l'activité de la Société les contrats liant le Client et de la Société et/ou successeurs et ayants droit conservent force obligatoire entre les parties. Les contrats passés par la Société ne pourront pas être cédés par l'Abonné et ou le Client sans consentement préalablement donné par écrit de la Société.

Les contrats, droits et obligations de la Société pourront en tout état de cause être cédés ou transférés sans accord préalable du Client.

 

## Article 22 - Publicité sur le Site

La Société peut en toute liberté insérer de la publicité sur son Site, et dispose d'une liberté totale de choix quant à la disposition de ces publicités, des annonceurs ainsi que de la visualisation de ces publicités.

 

## Article 23 - Modifications des Conditions Générales de Vente

La Société se réserve le droit de modifier en tout état de cause les présentes Conditions Générales de Vente.

Si les nouvelles Conditions Générales de Vente ne convenaient pas à un Client, ce dernier devrait résilier son abonnement par lettre recommandée avec accusé de réception avant l'entrée en vigueur des nouvelles dispositions suivant les modalités prévues à l'Article « faculté de rétractation » des présentes Conditions Générales de Vente.

Le refus des nouvelles Conditions Générales de Vente devra impérativement être explicite, sans manifestation explicite de volonté avant l'entrée en vigueur des nouvelles dispositions, le Client sera réputé avoir accepté les modifications.

 

## Article 24 - Loi applicable

Les présentes Conditions Générales de Vente sont soumises au droit français applicable indépendamment du pays de résidence du Client et du lieu de conclusion du contrat. L'application de la convention de Vienne sur la vente internationale de marchandises est expressément écartée.

L'Abonné reconnaît que les communications et registres informatisés du Site seront considérés par les parties comme preuve des échanges, commandes, paiements et transactions intervenues entre les parties sauf preuve contraire.

EOD;
        return $string;

    }

}
