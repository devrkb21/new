<?php

return [
	'mode' => 'utf-8',
	'format' => 'A4',
	'author' => '',
	'subject' => '',
	'keywords' => '',
	'creator' => 'Laravel Pdf',
	'display_mode' => 'fullpage',
	'tempDir' => base_path('../temp/'),
	'pdf_a' => false,
	'pdf_a_auto' => false,
	'icc_profile_path' => '',

	// --- ADD THIS LINE TO ENABLE COLOR ---
	'grayscale' => false,
	'color' => true,
	// ------------------------------------

	'default_font' => 'hindsiliguri',

	'font_path' => base_path('storage/fonts/'),
	'font_data' => [
		'hindsiliguri' => [
			'R' => 'HindSiliguri-Regular.ttf',
			'B' => 'HindSiliguri-Bold.ttf',
			'useOTL' => 0xFF,
			'useKashida' => 75,
		]
	],
];
