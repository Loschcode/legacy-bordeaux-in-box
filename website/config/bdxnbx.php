<?php

return [

	/**
	 * PAYMENTS
	 */
	'payment_ways' => [

		'stripe_card' => 'Carte bancaire - Stripe',
		'cheque' => 'Chèque - Manuel'

	],

	/**
	 * QUESTIONS / ANSWERS
	 */

	// No answer possible for those question types
	'no_answer_question_type' => ['text', 'textarea', 'date', 'member_email', 'children_details'],

	// Possible questions types
	'question_types' => [

			'0' => 'Choisir le type de question ...',

			'text' => 'Champs texte simple',
			'textarea' => 'Champs texte multiligne',
			'radiobutton' => 'Boutons radio',
			'checkbox' => 'Cases à cocher',

			'date' => 'Date à remplir',
			'member_email' => 'Email d\'un membre',
			'children_details' => 'Description des enfants',

		],

	'children_special_fields' => [

		'baby' => [

			'label' => 'Bébé',
			'min_age' => 0,

		],

		'kid' => [

			'label' => 'Enfant',
			'min_age' => 6,

		],

		'teen' => [

			'label' => 'Adolescent',
			'min_age' => 12,

		],

		'adult' => [

			'label' => 'Adulte',
			'min_age' => 18,

		],

	],

	'children_sex_fields' => [

		"Fille" => 'Fille',
		"Garçon" => 'Garçon',
		"Je ne sais pas encore" => 'Je ne sais pas encore'

	],


	'date_age_special_fields' => [

		'baby' => [

			'label' => 'Autre',
			'min_age' => 0,

		],

		'teen' => [

			'label' => 'Adolescent',
			'min_age' => 12,

		],

		'young_adult' => [

			'label' => 'Jeune adulte',
			'min_age' => 18,

		],


		'adult' => [

			'label' => 'Adulte',
			'min_age' => 23,

		],

	],

	/**
	 * FILTERS
	 */
	
	'filters_setup' => [

		'large_products' => 1,
		'medium_products' => 3,
		'small_products' => 5,

		'high_priority_difference' => 10,
		'low_priority_difference' => 10,

	],

	/**
	 * PRODUCTS
	 */
	'product_categories' => [

		'miam' => 'Miam',
		'beaute' => 'Beauté',

		'do-it-yourself' => 'Do It Yourself',
		'art' => 'Art',
		
		'maison' => 'Maison',
		'atelier' => 'Atelier',
		'enfants' => 'Enfants',
		'bijoux' => 'Bijoux',
		'bebe' => 'Bébé',
		
		'service' => 'Service',


	],

	/**
	 * BOXES
	 */

	// Spyro colors matching with specific slug boxes
	'box_spyro_color' => [

		'princesse-bichette' => 'spyro-btn-bichette',
		'poulette-du-ghetto' => 'spyro-btn-ghetto',
		'super-mamoune' => 'spyro-btn-mamoune'

	],

	// Colors matching with specific slug boxes
	'box_color' => [

		'princesse-bichette' => 'purple',
		'poulette-du-ghetto' => 'green',
		'super-mamoune' => 'orange',

	],

	/**
	 * PRODUCTS
	 */
	
	// Size products possibility
	'product_sizes' => [

		'minimum' => 'Petit produit',
		'medium' => 'Moyen produit',
		'maximum' => 'Gros produit',

	],

	/**
	 * CONTACT
	 */

	// Services to contact
	'contact_service' => [

			'0' => 'Choisir le service concerné ...',

			'Commercial' => [

				'com-question' => 'Question à propos de la box',
				'com-partner' => 'Proposition de partenariat',
				'com-delivery' => 'Problème de box défectueuse ou non reçue',

			],

			'Technique' => [

				'tech-idea' => 'Suggérer une amélioration pour le site',
				'tech-trans' => 'Signaler un problème de transaction bancaire',
				'tech-address' => 'Faire un changement sur mon compte',
				'tech-holidays' => 'Je pars en vacances',
				'tech-cancel' => 'Annuler mon abonnement',
				'tech-bug' => 'Signaler un bug',
			],

		],

];