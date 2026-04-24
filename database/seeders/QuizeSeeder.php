<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Step 1: seed the reward coupon used by the quiz result flow.
        DB::table('coupons')->upsert([
            [
                'code' => 'BIOGENIX15',
                'discount_type' => 'percent',
                'discount_value' => 15.00,
                'allow_with_bulk' => false,
                'allow_with_product_discount' => false,
                'allow_on_company_price' => false,
                'is_active' => true,
                'valid_from' => null,
                'valid_to' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['code'], ['discount_type', 'discount_value', 'allow_with_bulk', 'allow_with_product_discount', 'allow_on_company_price', 'is_active', 'valid_from', 'valid_to', 'updated_at']);

        // Step 2: seed the fixed diagnostic quiz questions used by the public quiz page.
        DB::table('diagnostic_quiz_questions')->upsert([
            [
                'id' => 1,
                'user_type' => 'common',
                'phase_title' => 'Biogenix Kits Mastery',
                'question_text' => 'Which reagent kit is best suited for high-throughput automation?',
                'question_support_details' => json_encode([
                    'show_question_badge' => true,
                    'sidebar_cards' => [
                        [
                            'card_type' => 'tip',
                            'eyebrow' => 'Clinical Tip',
                            'title' => 'Automation Integration',
                            'description' => 'Automation-compatible kits utilize standard SBS footprints and barcoded vials. When selecting a kit for high-throughput environments, prioritize those with liquid-level sensing compatibility to minimize aspiration errors.',
                        ],
                        [
                            'card_type' => 'context_list',
                            'title' => 'Assessment Context',
                            'items' => [
                                ['icon' => 'document', 'text' => 'Module: Reagent Classification'],
                                ['icon' => 'check-circle', 'text' => 'Difficulty: Intermediate'],
                                ['icon' => 'clock', 'text' => 'Time Limit: No constraints'],
                            ],
                        ],
                        [
                            'card_type' => 'image',
                            'image_path' => 'upload/corousel/image3.jpg',
                            'image_alt_text' => 'Automated pipetting system',
                            'image_caption' => 'Fig 1.1: Automated pipetting system with Biogenix reagents.',
                        ],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'display_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'user_type' => 'doctor',
                'phase_title' => 'Biogenix Kits Proficiency',
                'question_text' => 'What is the required storage temperature for the DNA Polymerase High Fidelity kit?',
                'question_support_details' => json_encode([
                    'show_question_badge' => false,
                    'sidebar_cards' => [
                        [
                            'card_type' => 'context_sections',
                            'title' => 'Storage Best Practices',
                            'sections' => [
                                [
                                    'title' => 'Enzymatic Stability',
                                    'description' => 'Most high-fidelity polymerases lose activity if exposed to repeated freeze-thaw cycles. Always use a cooling block during use.',
                                ],
                                [
                                    'title' => 'Reagent Segregation',
                                    'description' => 'Keep dNTPs and primers in separate aliquots to prevent cross-contamination during library preparation.',
                                ],
                            ],
                        ],
                        [
                            'card_type' => 'insight',
                            'eyebrow' => 'Clinical Insight',
                            'description' => 'Storing at -20°C in a non-frost-free freezer is critical for maintaining long-term buffer molarity.',
                        ],
                        [
                            'card_type' => 'reference_list',
                            'title' => 'Reference Material',
                            'items' => [
                                [
                                    'document_name' => 'Kit_Datasheet_V4.pdf',
                                    'leading_icon' => 'file-blue',
                                    'trailing_icon' => 'download',
                                ],
                                [
                                    'document_name' => 'Storage_Protocol_Guide',
                                    'leading_icon' => 'file-green',
                                    'trailing_icon' => 'external-link',
                                ],
                            ],
                        ],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'display_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'user_type' => 'doctor',
                'phase_title' => 'Compliance Standards',
                'question_text' => 'Which certification standard governs IVD reagent manufacturing quality?',
                'question_support_details' => json_encode([
                    'show_question_badge' => true,
                    'sidebar_cards' => [
                        [
                            'card_type' => 'tip',
                            'eyebrow' => 'Clinical Tip',
                            'title' => 'Regulatory Compliance',
                            'description' => 'ISO 13485 is the primary quality management standard for medical devices and IVD products. It ensures traceability, risk management, and process validation throughout the product lifecycle.',
                        ],
                        [
                            'card_type' => 'context_list',
                            'title' => 'Assessment Context',
                            'items' => [
                                ['icon' => 'document', 'text' => 'Module: Compliance Standards'],
                                ['icon' => 'check-circle', 'text' => 'Difficulty: Advanced'],
                            ],
                        ],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'display_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'user_type' => 'doctor',
                'phase_title' => 'Sample Preparation',
                'question_text' => 'Which sample preparation method yields highest DNA purity for NGS workflows?',
                'question_support_details' => json_encode([
                    'show_question_badge' => true,
                    'sidebar_cards' => [
                        [
                            'card_type' => 'tip',
                            'eyebrow' => 'Clinical Tip',
                            'title' => 'NGS Library Prep',
                            'description' => 'Magnetic bead-based purification provides the best combination of purity and automation compatibility for next-generation sequencing, with minimal carry-over contamination.',
                        ],
                        [
                            'card_type' => 'image',
                            'image_path' => 'upload/corousel/image5.jpg',
                            'image_alt_text' => 'NGS sample preparation',
                            'image_caption' => 'Fig 4.1: NGS library preparation workflow.',
                        ],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'display_order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'user_type' => 'lab',
                'phase_title' => 'Lab Equipment Optimization',
                'question_text' => 'What is the optimal sample throughput capacity for laboratory diagnostic workflows?',
                'question_support_details' => json_encode([
                    'show_question_badge' => true,
                    'sidebar_cards' => [
                        [
                            'card_type' => 'tip',
                            'eyebrow' => 'Lab Tip',
                            'title' => 'Throughput Planning',
                            'description' => 'Modern diagnostic labs require equipment capable of processing 100-500 samples daily with minimum error rates. Biogenix kits are designed for scalable throughput without compromising accuracy.',
                        ],
                        [
                            'card_type' => 'context_list',
                            'title' => 'Assessment Context',
                            'items' => [
                                ['icon' => 'document', 'text' => 'Module: Lab Operations'],
                                ['icon' => 'check-circle', 'text' => 'Difficulty: Intermediate'],
                            ],
                        ],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'display_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'user_type' => 'lab',
                'phase_title' => 'Quality Assurance Standards',
                'question_text' => 'Which quality control measure is essential for diagnostic lab accreditation?',
                'question_support_details' => json_encode([
                    'show_question_badge' => false,
                    'sidebar_cards' => [
                        [
                            'card_type' => 'tip',
                            'eyebrow' => 'Quality Insight',
                            'title' => 'Lab Accreditation',
                            'description' => 'CLIA and CAP accreditation require daily quality control checks, internal controls, and external proficiency testing to ensure reliability and accuracy of diagnostic results.',
                        ],
                        [
                            'card_type' => 'reference_list',
                            'title' => 'Compliance Documents',
                            'items' => [
                                [
                                    'document_name' => 'Quality_Control_Manual.pdf',
                                    'leading_icon' => 'file-blue',
                                    'trailing_icon' => 'download',
                                ],
                                [
                                    'document_name' => 'Lab_Standards_Checklist',
                                    'leading_icon' => 'file-green',
                                    'trailing_icon' => 'external-link',
                                ],
                            ],
                        ],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'display_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'user_type' => 'lab',
                'phase_title' => 'Test Result Turnaround',
                'question_text' => 'What is the most critical factor in maintaining fast diagnostic turnaround times?',
                'question_support_details' => json_encode([
                    'show_question_badge' => true,
                    'sidebar_cards' => [
                        [
                            'card_type' => 'tip',
                            'eyebrow' => 'Best Practice',
                            'title' => 'TAT Optimization',
                            'description' => 'Integrated workflow automation combined with ready-to-use Biogenix reagent kits eliminates setup time and reduces total testing cycle from hours to minutes.',
                        ],
                        [
                            'card_type' => 'image',
                            'image_path' => 'upload/corousel/image4.jpg',
                            'image_alt_text' => 'Lab turnaround time',
                            'image_caption' => 'Fig 3.1: Optimized diagnostic workflow timeline.',
                        ],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'display_order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'user_type' => 'dealer',
                'phase_title' => 'Supply Chain Management',
                'question_text' => 'What inventory management strategy maximizes profitability for diagnostic kit distribution?',
                'question_support_details' => json_encode([
                    'show_question_badge' => true,
                    'sidebar_cards' => [
                        [
                            'card_type' => 'tip',
                            'eyebrow' => 'Distribution Insight',
                            'title' => 'Inventory Optimization',
                            'description' => 'Smart forecasting and just-in-time ordering of Biogenix kits reduces capital lock-up while ensuring 99.5% order fulfillment rates. Margin acceleration happens through volume commitments.',
                        ],
                        [
                            'card_type' => 'context_list',
                            'title' => 'Assessment Context',
                            'items' => [
                                ['icon' => 'document', 'text' => 'Module: Distribution'],
                                ['icon' => 'check-circle', 'text' => 'Difficulty: Intermediate'],
                            ],
                        ],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'display_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 9,
                'user_type' => 'dealer',
                'phase_title' => 'Customer Relationship Management',
                'question_text' => 'Which support service drives customer retention and repeat orders?',
                'question_support_details' => json_encode([
                    'show_question_badge' => false,
                    'sidebar_cards' => [
                        [
                            'card_type' => 'tip',
                            'eyebrow' => 'Business Strategy',
                            'title' => 'Customer Success',
                            'description' => 'Biogenix offers dedicated support teams, on-site training, troubleshooting hotlines, and quarterly business reviews. Dealers who leverage these services see 3x customer lifetime value.',
                        ],
                        [
                            'card_type' => 'reference_list',
                            'title' => 'Dealer Resources',
                            'items' => [
                                [
                                    'document_name' => 'Channel_Partner_Guide.pdf',
                                    'leading_icon' => 'file-blue',
                                    'trailing_icon' => 'download',
                                ],
                                [
                                    'document_name' => 'Training_Program_Schedule',
                                    'leading_icon' => 'file-green',
                                    'trailing_icon' => 'external-link',
                                ],
                            ],
                        ],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'display_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 10,
                'user_type' => 'dealer',
                'phase_title' => 'Pricing and Margins',
                'question_text' => 'How do tiered pricing models impact dealer profitability in the diagnostics sector?',
                'question_support_details' => json_encode([
                    'show_question_badge' => true,
                    'sidebar_cards' => [
                        [
                            'card_type' => 'tip',
                            'eyebrow' => 'Pricing Strategy',
                            'title' => 'Margin Optimization',
                            'description' => 'Volume-based tiered pricing at 10K, 25K, and 50K unit thresholds allows dealers to scale margins while maintaining competitive positioning. Bulk orders unlock additional rebate programs.',
                        ],
                        [
                            'card_type' => 'image',
                            'image_path' => 'upload/corousel/image2.jpg',
                            'image_alt_text' => 'Pricing tiers',
                            'image_caption' => 'Fig 2.1: Tiered pricing structure for dealer partners.',
                        ],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'display_order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 11,
                'user_type' => 'other',
                'phase_title' => 'Product Knowledge Fundamentals',
                'question_text' => 'What defines a Biogenix diagnostic kit\'s primary competitive advantage?',
                'question_support_details' => json_encode([
                    'show_question_badge' => true,
                    'sidebar_cards' => [
                        [
                            'card_type' => 'tip',
                            'eyebrow' => 'Product Insight',
                            'title' => 'Biogenix Advantage',
                            'description' => 'Biogenix kits deliver superior accuracy, faster results, and seamless integration with existing laboratory infrastructure. Our formulations are designed with clinician feedback for real-world performance.',
                        ],
                        [
                            'card_type' => 'context_list',
                            'title' => 'Assessment Context',
                            'items' => [
                                ['icon' => 'document', 'text' => 'Module: Product Overview'],
                                ['icon' => 'check-circle', 'text' => 'Difficulty: Beginner'],
                            ],
                        ],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'display_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 12,
                'user_type' => 'other',
                'phase_title' => 'Industry Standards Awareness',
                'question_text' => 'Which international certification is most recognized for IVD product reliability?',
                'question_support_details' => json_encode([
                    'show_question_badge' => false,
                    'sidebar_cards' => [
                        [
                            'card_type' => 'tip',
                            'eyebrow' => 'Certification Knowledge',
                            'title' => 'Global Standards',
                            'description' => 'ISO 13485 certification for medical device quality, combined with regional approvals (CE mark, FDA clearance), ensures Biogenix products meet the strictest international standards.',
                        ],
                        [
                            'card_type' => 'reference_list',
                            'title' => 'Certification Documents',
                            'items' => [
                                [
                                    'document_name' => 'ISO_13485_Certificate.pdf',
                                    'leading_icon' => 'file-blue',
                                    'trailing_icon' => 'download',
                                ],
                                [
                                    'document_name' => 'Regulatory_Approvals_Summary',
                                    'leading_icon' => 'file-green',
                                    'trailing_icon' => 'external-link',
                                ],
                            ],
                        ],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'display_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 13,
                'user_type' => 'other',
                'phase_title' => 'Market Opportunity',
                'question_text' => 'What is the primary market growth driver for in-vitro diagnostic products?',
                'question_support_details' => json_encode([
                    'show_question_badge' => true,
                    'sidebar_cards' => [
                        [
                            'card_type' => 'tip',
                            'eyebrow' => 'Market Insight',
                            'title' => 'IVD Sector Growth',
                            'description' => 'Rising chronic disease prevalence, aging populations, and demand for rapid testing drive 8-10% annual IVD market growth. Biogenix is positioned at the intersection of innovation and affordability.',
                        ],
                        [
                            'card_type' => 'image',
                            'image_path' => 'upload/corousel/image1.jpg',
                            'image_alt_text' => 'Market growth',
                            'image_caption' => 'Fig 1.2: Global IVD market projections 2024-2030.',
                        ],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'display_order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['id'], ['user_type', 'phase_title', 'question_text', 'question_support_details', 'display_order', 'updated_at']);

        // Step 3: seed the answer options used for scoring and display.
        DB::table('diagnostic_quiz_answer_options')->upsert([
            // Question 1 (Common)
            ['id' => 1, 'question_id' => 1, 'option_label' => 'A', 'option_text' => 'Precision-X LIMS Kit', 'is_correct_answer' => true, 'display_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'question_id' => 1, 'option_label' => 'B', 'option_text' => 'Bio-RGT Standard', 'is_correct_answer' => false, 'display_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'question_id' => 1, 'option_label' => 'C', 'option_text' => 'Clinical-Max Assay', 'is_correct_answer' => false, 'display_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'question_id' => 1, 'option_label' => 'D', 'option_text' => 'Eco-Lite Consumable', 'is_correct_answer' => false, 'display_order' => 4, 'created_at' => $now, 'updated_at' => $now],

            // Question 2 (Doctor)
            ['id' => 5, 'question_id' => 2, 'option_label' => 'A', 'option_text' => 'Room Temperature', 'is_correct_answer' => false, 'display_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'question_id' => 2, 'option_label' => 'B', 'option_text' => '4°C', 'is_correct_answer' => false, 'display_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'question_id' => 2, 'option_label' => 'C', 'option_text' => '-20°C', 'is_correct_answer' => true, 'display_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'question_id' => 2, 'option_label' => 'D', 'option_text' => '-80°C', 'is_correct_answer' => false, 'display_order' => 4, 'created_at' => $now, 'updated_at' => $now],

            // Question 3 (Doctor)
            ['id' => 9, 'question_id' => 3, 'option_label' => 'A', 'option_text' => 'ISO 9001:2015', 'is_correct_answer' => false, 'display_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'question_id' => 3, 'option_label' => 'B', 'option_text' => 'ISO 13485:2016', 'is_correct_answer' => true, 'display_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'question_id' => 3, 'option_label' => 'C', 'option_text' => 'CE-IVD Directive 98/79/EC', 'is_correct_answer' => false, 'display_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 12, 'question_id' => 3, 'option_label' => 'D', 'option_text' => 'GMP Annex 15', 'is_correct_answer' => false, 'display_order' => 4, 'created_at' => $now, 'updated_at' => $now],

            // Question 4 (Doctor)
            ['id' => 13, 'question_id' => 4, 'option_label' => 'A', 'option_text' => 'Phenol-chloroform extraction', 'is_correct_answer' => false, 'display_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 14, 'question_id' => 4, 'option_label' => 'B', 'option_text' => 'Magnetic bead-based purification', 'is_correct_answer' => true, 'display_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 15, 'question_id' => 4, 'option_label' => 'C', 'option_text' => 'Silica membrane column', 'is_correct_answer' => false, 'display_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 16, 'question_id' => 4, 'option_label' => 'D', 'option_text' => 'Salting-out precipitation', 'is_correct_answer' => false, 'display_order' => 4, 'created_at' => $now, 'updated_at' => $now],

            // Question 5 (Lab - Throughput)
            ['id' => 17, 'question_id' => 5, 'option_label' => 'A', 'option_text' => '10-20 samples daily', 'is_correct_answer' => false, 'display_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 18, 'question_id' => 5, 'option_label' => 'B', 'option_text' => '100-500 samples daily', 'is_correct_answer' => true, 'display_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 19, 'question_id' => 5, 'option_label' => 'C', 'option_text' => '1000+ samples daily', 'is_correct_answer' => false, 'display_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 20, 'question_id' => 5, 'option_label' => 'D', 'option_text' => 'No specific capacity needed', 'is_correct_answer' => false, 'display_order' => 4, 'created_at' => $now, 'updated_at' => $now],

            // Question 6 (Lab - QA)
            ['id' => 21, 'question_id' => 6, 'option_label' => 'A', 'option_text' => 'Monthly quality reviews', 'is_correct_answer' => false, 'display_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 22, 'question_id' => 6, 'option_label' => 'B', 'option_text' => 'Daily QC checks and proficiency testing', 'is_correct_answer' => true, 'display_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 23, 'question_id' => 6, 'option_label' => 'C', 'option_text' => 'Annual audits only', 'is_correct_answer' => false, 'display_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 24, 'question_id' => 6, 'option_label' => 'D', 'option_text' => 'Spot checks as needed', 'is_correct_answer' => false, 'display_order' => 4, 'created_at' => $now, 'updated_at' => $now],

            // Question 7 (Lab - TAT)
            ['id' => 25, 'question_id' => 7, 'option_label' => 'A', 'option_text' => 'Manual sample processing', 'is_correct_answer' => false, 'display_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 26, 'question_id' => 7, 'option_label' => 'B', 'option_text' => 'Integrated workflow automation', 'is_correct_answer' => true, 'display_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 27, 'question_id' => 7, 'option_label' => 'C', 'option_text' => 'Outsourcing tests to reference labs', 'is_correct_answer' => false, 'display_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 28, 'question_id' => 7, 'option_label' => 'D', 'option_text' => 'Batch processing only', 'is_correct_answer' => false, 'display_order' => 4, 'created_at' => $now, 'updated_at' => $now],

            // Question 8 (Dealer - Inventory)
            ['id' => 29, 'question_id' => 8, 'option_label' => 'A', 'option_text' => 'Large stockpiling approach', 'is_correct_answer' => false, 'display_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 30, 'question_id' => 8, 'option_label' => 'B', 'option_text' => 'Just-in-time ordering with forecasting', 'is_correct_answer' => true, 'display_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 31, 'question_id' => 8, 'option_label' => 'C', 'option_text' => 'Minimal inventory with frequent backorders', 'is_correct_answer' => false, 'display_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 32, 'question_id' => 8, 'option_label' => 'D', 'option_text' => 'No planned inventory management', 'is_correct_answer' => false, 'display_order' => 4, 'created_at' => $now, 'updated_at' => $now],

            // Question 9 (Dealer - CRM)
            ['id' => 33, 'question_id' => 9, 'option_label' => 'A', 'option_text' => 'Competitive pricing alone', 'is_correct_answer' => false, 'display_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 34, 'question_id' => 9, 'option_label' => 'B', 'option_text' => 'Dedicated support and training programs', 'is_correct_answer' => true, 'display_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 35, 'question_id' => 9, 'option_label' => 'C', 'option_text' => 'Monthly sales calls', 'is_correct_answer' => false, 'display_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 36, 'question_id' => 9, 'option_label' => 'D', 'option_text' => 'Self-service only platform', 'is_correct_answer' => false, 'display_order' => 4, 'created_at' => $now, 'updated_at' => $now],

            // Question 10 (Dealer - Pricing)
            ['id' => 37, 'question_id' => 10, 'option_label' => 'A', 'option_text' => 'Fixed pricing regardless of volume', 'is_correct_answer' => false, 'display_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 38, 'question_id' => 10, 'option_label' => 'B', 'option_text' => 'Volume-based tiered pricing with rebates', 'is_correct_answer' => true, 'display_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 39, 'question_id' => 10, 'option_label' => 'C', 'option_text' => 'Discounts only for annual contracts', 'is_correct_answer' => false, 'display_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 40, 'question_id' => 10, 'option_label' => 'D', 'option_text' => 'Negotiated pricing per customer', 'is_correct_answer' => false, 'display_order' => 4, 'created_at' => $now, 'updated_at' => $now],

            // Question 11 (Other - Product)
            ['id' => 41, 'question_id' => 11, 'option_label' => 'A', 'option_text' => 'Lowest cost to market', 'is_correct_answer' => false, 'display_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 42, 'question_id' => 11, 'option_label' => 'B', 'option_text' => 'Superior accuracy and workflow integration', 'is_correct_answer' => true, 'display_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 43, 'question_id' => 11, 'option_label' => 'C', 'option_text' => 'Largest product portfolio', 'is_correct_answer' => false, 'display_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 44, 'question_id' => 11, 'option_label' => 'D', 'option_text' => 'Fastest manufacturing speed', 'is_correct_answer' => false, 'display_order' => 4, 'created_at' => $now, 'updated_at' => $now],

            // Question 12 (Other - Standards)
            ['id' => 45, 'question_id' => 12, 'option_label' => 'A', 'option_text' => 'ISO 9001', 'is_correct_answer' => false, 'display_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 46, 'question_id' => 12, 'option_label' => 'B', 'option_text' => 'ISO 13485 and regional approvals', 'is_correct_answer' => true, 'display_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 47, 'question_id' => 12, 'option_label' => 'C', 'option_text' => 'Internal company standards', 'is_correct_answer' => false, 'display_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 48, 'question_id' => 12, 'option_label' => 'D', 'option_text' => 'No specific certification required', 'is_correct_answer' => false, 'display_order' => 4, 'created_at' => $now, 'updated_at' => $now],

            // Question 13 (Other - Market)
            ['id' => 49, 'question_id' => 13, 'option_label' => 'A', 'option_text' => 'Declining healthcare budgets', 'is_correct_answer' => false, 'display_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 50, 'question_id' => 13, 'option_label' => 'B', 'option_text' => 'Rising chronic diseases and aging populations', 'is_correct_answer' => true, 'display_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 51, 'question_id' => 13, 'option_label' => 'C', 'option_text' => 'Shift away from diagnostic testing', 'is_correct_answer' => false, 'display_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 52, 'question_id' => 13, 'option_label' => 'D', 'option_text' => 'Regulatory restrictions on testing', 'is_correct_answer' => false, 'display_order' => 4, 'created_at' => $now, 'updated_at' => $now],
        ], ['id'], ['question_id', 'option_label', 'option_text', 'is_correct_answer', 'display_order', 'updated_at']);
    }
}
