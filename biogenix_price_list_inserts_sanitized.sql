-- ============================================================
-- Biogenix Product Price List FY 2025-2026
-- Sanitized MySQL import for the production category master
-- Source: biogenix_price_list_inserts.sql
-- Changes:
--   - removed LAST_INSERT_ID() and session-variable dependencies
--   - assigned deterministic IDs for products, variants, prices, and bulk prices
--   - aligned category IDs to the production 15-category master
--   - set subcategory_id = NULL for all imported products
--   - added targeted cleanup so the script can be re-run safely
-- Reserved ID ranges:
--   products: 910001-910094
--   product_variants: 920001-920094
--   product_prices: 930001-930282
--   product_bulk_prices: 940001-940222
-- ============================================================

START TRANSACTION;

-- Ensure the production category master exists.
INSERT INTO categories (id, name, description, application, slug, IsDisplayedOnHomePage, default_image_path, gst_rate, sort_order, created_at, updated_at)
VALUES
  (1, 'Biochemistry', 'Biochemistry reagents, controls, calibrators, and analyzer-linked chemistry products.', 'Clinical chemistry labs, diagnostic centers, hospitals, and pathology workflows', 'biochemistry', 1, 'upload/categories/image1.jpg', 18.00, 1, NOW(), NOW()),
  (2, 'Blood Culture Bottle', 'Blood culture bottles and related microbiology sample collection products.', 'Microbiology labs, hospitals, and blood culture processing workflows', 'blood-culture-bottle', 1, 'upload/categories/image2.jpg', 18.00, 2, NOW(), NOW()),
  (3, 'Elisa Kits', 'ELISA kits, assay systems, and ELISA workflow products.', 'ELISA testing labs, immunoassay workflows, and research facilities', 'elisa-kits', 1, 'upload/categories/image3.jpg', 18.00, 3, NOW(), NOW()),
  (4, 'Haematology', 'Haematology reagents, analyzers, and CBC workflow products.', 'Haematology labs, pathology labs, and hospital laboratory departments', 'haematology', 1, 'upload/categories/image4.jpg', 18.00, 4, NOW(), NOW()),
  (5, 'Instrument', 'General laboratory and diagnostic instruments outside the IVD analyzer range.', 'Hospitals, clinics, and laboratory operations requiring diagnostic instruments', 'instrument', 1, 'upload/categories/image5.jpg', 18.00, 5, NOW(), NOW()),
  (6, 'POCT', 'Point-of-care testing analyzers, kits, and rapid near-patient workflows.', 'Critical care, emergency, physician office, and decentralized testing setups', 'poct', 1, 'upload/categories/image1.jpg', 18.00, 6, NOW(), NOW()),
  (7, 'Rapid', 'Rapid diagnostic tests for screening and quick turnaround workflows.', 'Hospitals, clinics, camps, laboratories, and decentralized diagnostics', 'rapid', 1, 'upload/categories/image2.jpg', 18.00, 7, NOW(), NOW()),
  (8, 'Serology', 'Serology kits, latex reagents, and immunology workflow products.', 'Serology labs, immunology testing, and hospital laboratory departments', 'serology', 1, 'upload/categories/image3.jpg', 18.00, 8, NOW(), NOW()),
  (9, 'Urinalysis', 'Urinalysis strips, analyzers, and routine urine testing products.', 'Routine urinalysis labs, hospitals, and diagnostic centers', 'urinalysis', 1, 'upload/categories/image4.jpg', 18.00, 9, NOW(), NOW()),
  (10, 'Special Chemistry', 'Special chemistry assays including turbidimetry and advanced chemistry workflows.', 'Special chemistry testing, advanced clinical chemistry, and reference labs', 'special-chemistry', 1, 'upload/categories/image5.jpg', 18.00, 10, NOW(), NOW()),
  (11, 'CLIA', 'CLIA reagents, calibrators, controls, and analyzer-linked immunoassay products.', 'Chemiluminescence laboratories, immunoassay testing, and hospital labs', 'clia', 1, 'upload/categories/image1.jpg', 18.00, 11, NOW(), NOW()),
  (12, 'Veterinary', 'Veterinary diagnostic reagents, kits, and analyzer-compatible products.', 'Veterinary clinics, animal diagnostics, and research institutions', 'veterinary', 1, 'upload/categories/image2.jpg', 18.00, 12, NOW(), NOW()),
  (13, 'Molecular', 'Molecular diagnostics kits, PCR products, and nucleic-acid testing workflows.', 'Molecular labs, PCR setups, infectious disease testing, and research labs', 'molecular', 1, 'upload/categories/image3.jpg', 18.00, 13, NOW(), NOW()),
  (14, 'Microbiology', 'Microbiology media, culture systems, and organism detection products.', 'Microbiology departments, culture workflows, and hospital laboratories', 'microbiology', 1, 'upload/categories/image4.jpg', 18.00, 14, NOW(), NOW()),
  (15, 'IVD Instruments', 'In-vitro diagnostic analyzers and related instrument platforms.', 'Diagnostic laboratories, hospital labs, and institutional IVD installations', 'ivd-instruments', 1, 'upload/categories/image5.jpg', 18.00, 15, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  description = VALUES(description),
  application = VALUES(application),
  slug = VALUES(slug),
  IsDisplayedOnHomePage = VALUES(IsDisplayedOnHomePage),
  default_image_path = VALUES(default_image_path),
  gst_rate = VALUES(gst_rate),
  sort_order = VALUES(sort_order),
  updated_at = VALUES(updated_at);

-- Cleanup for safe re-runs.
DELETE FROM product_bulk_prices WHERE id BETWEEN 940001 AND 940222;
DELETE FROM product_prices WHERE id BETWEEN 930001 AND 930282;

DELETE pbp
FROM product_bulk_prices pbp
INNER JOIN product_variants pv ON pv.id = pbp.product_variant_id
WHERE pv.sku IN (
  'BIO-BICC-001-15ML', 'BIO-BICC-003-100ML', 'BIO-BICC-004-100ML', 'BIO-BICC-005-10ML', 'BIO-BICC-007-2X50ML', 'BIO-BICC-008-100ML',
  'BIO-BICC-009-100ML', 'BIO-BICC-010-100ML', 'BIO-BICC-011-40ML', 'BIO-BICC-013-40ML', 'BIO-BICC-014-100ML', 'BIO-BICC-015-48ML',
  'BIO-BICC-017-10ML', 'BIO-BICC-018-10ML', 'BIO-BICC-019-40ML', 'BIO-BICC-020-100ML', 'BIO-BICC-021-40ML', 'BIO-BICC-022-50ML',
  'BIO-BICC-024-40ML', 'BIO-BICC-025-40ML', 'BIO-BICC-026-25ML', 'BIO-BICC-027-20ML', 'BIO-BICC-028-50ML', 'BIO-BICC-029-40ML',
  'BIO-BICC-030-50ML', 'BIO-BICC-031-10ML', 'BIO-BICC-032-100ML', 'BIO-BICC-033-100ML', 'BIO-BICC-034-100ML', 'BIO-BICC-035-100ML',
  'BIO-BICC-036-100ML', 'BIO-BICC-037-100ML', 'BIO-BICC-039-100ML', 'BIO-BICC-040-100ML', 'BIO-BICC-041-100ML', 'BIO-BICC-042-100ML',
  'BIO-BICC-043-50ML', 'BIO-BIT-001-50ML', 'BIO-BIT-002-50ML', 'BIO-BIT-003-50ML', 'BIO-BIT-004-40ML', 'BIO-BIT-005-40ML',
  'BIO-BIR-003-50T', 'BIO-BIR-004-40T', 'BIO-BIR-009-40T', 'BIO-BIR-010-25T', 'BIO-BIR-015-50T', 'BIO-BIR-016-50T',
  'BIO-BIR-017M-50T', 'BIO-BIR-018S-50T', 'BIO-BIR-022-50T', 'BIO-BIR-023-50T', 'BIO-BIR-024S-50T', 'BIO-BIR-021-50T',
  'BIO-BIR-034-30T', 'BIO-BIU-001-100T', 'BIO-BIU-003-100T', 'BIO-BIU-008-100T', 'BIO-BIS-001-25T', 'BIO-BIS-002-25T',
  'BIO-BIS-003-25T', 'BIO-BIS-004-50T', 'BIO-BIH-001-20LTR', 'BIO-BIH-002-500ML', 'BIO-BIH-003-20LTR', 'BIO-BIH-004-100ML',
  'BIO-BIH-005-100ML', 'BIO-BIM-002-100T', 'BIO-CLIA-TSH-100T', 'BIO-CLIA-VITD-100T', 'BIO-CLIA-AFP-100T', 'BIO-CLIA-CEA-100T',
  'BIO-CLIA-HBSAG-100T', 'BIO-CLIA-PCT-100T', 'BIO-INST-H30PRO-BASE', 'BIO-INST-H60-BASE', 'BIO-INST-H60S-BASE', 'BIO-INST-BI10S-BASE',
  'BIO-INST-BI280-BASE', 'BIO-INST-BI1000-BASE', 'BIO-INST-BI2000-BASE', 'BIO-INST-BI100CL-BASE', 'BIO-INST-BI200CL-BASE', 'BIO-INST-BI400CL-BASE',
  'BIO-INST-BI400-BASE', 'BIO-INST-BI180-BASE', 'BIO-INST-BI32-BASE', 'BIO-INST-BI3000PT-BASE', 'BIO-INST-BILYTE-BASE', 'BIO-INST-I15-BASE',
  'BIO-INST-BIR21-BASE', 'BIO-INST-BIW31-BASE', 'BIO-BGC-G10-25CART', 'BIO-HBA-CP100-100T'
);

DELETE pp
FROM product_prices pp
INNER JOIN product_variants pv ON pv.id = pp.product_variant_id
WHERE pv.sku IN (
  'BIO-BICC-001-15ML', 'BIO-BICC-003-100ML', 'BIO-BICC-004-100ML', 'BIO-BICC-005-10ML', 'BIO-BICC-007-2X50ML', 'BIO-BICC-008-100ML',
  'BIO-BICC-009-100ML', 'BIO-BICC-010-100ML', 'BIO-BICC-011-40ML', 'BIO-BICC-013-40ML', 'BIO-BICC-014-100ML', 'BIO-BICC-015-48ML',
  'BIO-BICC-017-10ML', 'BIO-BICC-018-10ML', 'BIO-BICC-019-40ML', 'BIO-BICC-020-100ML', 'BIO-BICC-021-40ML', 'BIO-BICC-022-50ML',
  'BIO-BICC-024-40ML', 'BIO-BICC-025-40ML', 'BIO-BICC-026-25ML', 'BIO-BICC-027-20ML', 'BIO-BICC-028-50ML', 'BIO-BICC-029-40ML',
  'BIO-BICC-030-50ML', 'BIO-BICC-031-10ML', 'BIO-BICC-032-100ML', 'BIO-BICC-033-100ML', 'BIO-BICC-034-100ML', 'BIO-BICC-035-100ML',
  'BIO-BICC-036-100ML', 'BIO-BICC-037-100ML', 'BIO-BICC-039-100ML', 'BIO-BICC-040-100ML', 'BIO-BICC-041-100ML', 'BIO-BICC-042-100ML',
  'BIO-BICC-043-50ML', 'BIO-BIT-001-50ML', 'BIO-BIT-002-50ML', 'BIO-BIT-003-50ML', 'BIO-BIT-004-40ML', 'BIO-BIT-005-40ML',
  'BIO-BIR-003-50T', 'BIO-BIR-004-40T', 'BIO-BIR-009-40T', 'BIO-BIR-010-25T', 'BIO-BIR-015-50T', 'BIO-BIR-016-50T',
  'BIO-BIR-017M-50T', 'BIO-BIR-018S-50T', 'BIO-BIR-022-50T', 'BIO-BIR-023-50T', 'BIO-BIR-024S-50T', 'BIO-BIR-021-50T',
  'BIO-BIR-034-30T', 'BIO-BIU-001-100T', 'BIO-BIU-003-100T', 'BIO-BIU-008-100T', 'BIO-BIS-001-25T', 'BIO-BIS-002-25T',
  'BIO-BIS-003-25T', 'BIO-BIS-004-50T', 'BIO-BIH-001-20LTR', 'BIO-BIH-002-500ML', 'BIO-BIH-003-20LTR', 'BIO-BIH-004-100ML',
  'BIO-BIH-005-100ML', 'BIO-BIM-002-100T', 'BIO-CLIA-TSH-100T', 'BIO-CLIA-VITD-100T', 'BIO-CLIA-AFP-100T', 'BIO-CLIA-CEA-100T',
  'BIO-CLIA-HBSAG-100T', 'BIO-CLIA-PCT-100T', 'BIO-INST-H30PRO-BASE', 'BIO-INST-H60-BASE', 'BIO-INST-H60S-BASE', 'BIO-INST-BI10S-BASE',
  'BIO-INST-BI280-BASE', 'BIO-INST-BI1000-BASE', 'BIO-INST-BI2000-BASE', 'BIO-INST-BI100CL-BASE', 'BIO-INST-BI200CL-BASE', 'BIO-INST-BI400CL-BASE',
  'BIO-INST-BI400-BASE', 'BIO-INST-BI180-BASE', 'BIO-INST-BI32-BASE', 'BIO-INST-BI3000PT-BASE', 'BIO-INST-BILYTE-BASE', 'BIO-INST-I15-BASE',
  'BIO-INST-BIR21-BASE', 'BIO-INST-BIW31-BASE', 'BIO-BGC-G10-25CART', 'BIO-HBA-CP100-100T'
);

DELETE FROM product_variants
WHERE id BETWEEN 920001 AND 920094
   OR sku IN (
  'BIO-BICC-001-15ML', 'BIO-BICC-003-100ML', 'BIO-BICC-004-100ML', 'BIO-BICC-005-10ML', 'BIO-BICC-007-2X50ML', 'BIO-BICC-008-100ML',
  'BIO-BICC-009-100ML', 'BIO-BICC-010-100ML', 'BIO-BICC-011-40ML', 'BIO-BICC-013-40ML', 'BIO-BICC-014-100ML', 'BIO-BICC-015-48ML',
  'BIO-BICC-017-10ML', 'BIO-BICC-018-10ML', 'BIO-BICC-019-40ML', 'BIO-BICC-020-100ML', 'BIO-BICC-021-40ML', 'BIO-BICC-022-50ML',
  'BIO-BICC-024-40ML', 'BIO-BICC-025-40ML', 'BIO-BICC-026-25ML', 'BIO-BICC-027-20ML', 'BIO-BICC-028-50ML', 'BIO-BICC-029-40ML',
  'BIO-BICC-030-50ML', 'BIO-BICC-031-10ML', 'BIO-BICC-032-100ML', 'BIO-BICC-033-100ML', 'BIO-BICC-034-100ML', 'BIO-BICC-035-100ML',
  'BIO-BICC-036-100ML', 'BIO-BICC-037-100ML', 'BIO-BICC-039-100ML', 'BIO-BICC-040-100ML', 'BIO-BICC-041-100ML', 'BIO-BICC-042-100ML',
  'BIO-BICC-043-50ML', 'BIO-BIT-001-50ML', 'BIO-BIT-002-50ML', 'BIO-BIT-003-50ML', 'BIO-BIT-004-40ML', 'BIO-BIT-005-40ML',
  'BIO-BIR-003-50T', 'BIO-BIR-004-40T', 'BIO-BIR-009-40T', 'BIO-BIR-010-25T', 'BIO-BIR-015-50T', 'BIO-BIR-016-50T',
  'BIO-BIR-017M-50T', 'BIO-BIR-018S-50T', 'BIO-BIR-022-50T', 'BIO-BIR-023-50T', 'BIO-BIR-024S-50T', 'BIO-BIR-021-50T',
  'BIO-BIR-034-30T', 'BIO-BIU-001-100T', 'BIO-BIU-003-100T', 'BIO-BIU-008-100T', 'BIO-BIS-001-25T', 'BIO-BIS-002-25T',
  'BIO-BIS-003-25T', 'BIO-BIS-004-50T', 'BIO-BIH-001-20LTR', 'BIO-BIH-002-500ML', 'BIO-BIH-003-20LTR', 'BIO-BIH-004-100ML',
  'BIO-BIH-005-100ML', 'BIO-BIM-002-100T', 'BIO-CLIA-TSH-100T', 'BIO-CLIA-VITD-100T', 'BIO-CLIA-AFP-100T', 'BIO-CLIA-CEA-100T',
  'BIO-CLIA-HBSAG-100T', 'BIO-CLIA-PCT-100T', 'BIO-INST-H30PRO-BASE', 'BIO-INST-H60-BASE', 'BIO-INST-H60S-BASE', 'BIO-INST-BI10S-BASE',
  'BIO-INST-BI280-BASE', 'BIO-INST-BI1000-BASE', 'BIO-INST-BI2000-BASE', 'BIO-INST-BI100CL-BASE', 'BIO-INST-BI200CL-BASE', 'BIO-INST-BI400CL-BASE',
  'BIO-INST-BI400-BASE', 'BIO-INST-BI180-BASE', 'BIO-INST-BI32-BASE', 'BIO-INST-BI3000PT-BASE', 'BIO-INST-BILYTE-BASE', 'BIO-INST-I15-BASE',
  'BIO-INST-BIR21-BASE', 'BIO-INST-BIW31-BASE', 'BIO-BGC-G10-25CART', 'BIO-HBA-CP100-100T'
);

DELETE FROM products
WHERE id BETWEEN 910001 AND 910094
   OR sku IN (
  'BIO-BICC-001', 'BIO-BICC-003', 'BIO-BICC-004', 'BIO-BICC-005', 'BIO-BICC-007', 'BIO-BICC-008',
  'BIO-BICC-009', 'BIO-BICC-010', 'BIO-BICC-011', 'BIO-BICC-013', 'BIO-BICC-014', 'BIO-BICC-015',
  'BIO-BICC-017', 'BIO-BICC-018', 'BIO-BICC-019', 'BIO-BICC-020', 'BIO-BICC-021', 'BIO-BICC-022',
  'BIO-BICC-024', 'BIO-BICC-025', 'BIO-BICC-026', 'BIO-BICC-027', 'BIO-BICC-028', 'BIO-BICC-029',
  'BIO-BICC-030', 'BIO-BICC-031', 'BIO-BICC-032', 'BIO-BICC-033', 'BIO-BICC-034', 'BIO-BICC-035',
  'BIO-BICC-036', 'BIO-BICC-037', 'BIO-BICC-039', 'BIO-BICC-040', 'BIO-BICC-041', 'BIO-BICC-042',
  'BIO-BICC-043', 'BIO-BIT-001', 'BIO-BIT-002', 'BIO-BIT-003', 'BIO-BIT-004', 'BIO-BIT-005',
  'BIO-BIR-003', 'BIO-BIR-004', 'BIO-BIR-009', 'BIO-BIR-010', 'BIO-BIR-015', 'BIO-BIR-016',
  'BIO-BIR-017M', 'BIO-BIR-018S', 'BIO-BIR-022', 'BIO-BIR-023', 'BIO-BIR-024S', 'BIO-BIR-021',
  'BIO-BIR-034', 'BIO-BIU-001', 'BIO-BIU-003', 'BIO-BIU-008', 'BIO-BIS-001', 'BIO-BIS-002',
  'BIO-BIS-003', 'BIO-BIS-004', 'BIO-BIH-001', 'BIO-BIH-002', 'BIO-BIH-003', 'BIO-BIH-004',
  'BIO-BIH-005', 'BIO-BIM-002', 'BIO-CLIA-TSH', 'BIO-CLIA-VITD', 'BIO-CLIA-AFP', 'BIO-CLIA-CEA',
  'BIO-CLIA-HBSAG', 'BIO-CLIA-PCT', 'BIO-INST-H30PRO', 'BIO-INST-H60', 'BIO-INST-H60S', 'BIO-INST-BI10S',
  'BIO-INST-BI280', 'BIO-INST-BI1000', 'BIO-INST-BI2000', 'BIO-INST-BI100CL', 'BIO-INST-BI200CL', 'BIO-INST-BI400CL',
  'BIO-INST-BI400', 'BIO-INST-BI180', 'BIO-INST-BI32', 'BIO-INST-BI3000PT', 'BIO-INST-BILYTE', 'BIO-INST-I15',
  'BIO-INST-BIR21', 'BIO-INST-BIW31', 'BIO-BGC-G10', 'BIO-HBA-CP100'
);

-- ============================================================
-- SECTION 1: CLINICAL CHEMISTRY
-- ============================================================

-- Product: ACID PHOSPHATASE (BICC-001, 15ml)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910001, 1, NULL, 'acid-phosphatase-bicc-001', 'BIO-BICC', 1, 'BIO-BICC-001', 'Acid Phosphatase', 'Biogenix', 'Clinical chemistry reagent kit for acid phosphatase measurement.', 'Acid Phosphatase reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920001, 910001, 'BIO-BICC-001-15ML', '15 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930001, 920001, 'public', 950.00, 'cash', 0.00, 12.00, 114.00, 1064.00, 'INR', 1),
  (930002, 920001, 'retail', 950.00, 'cash', 0.00, 12.00, 114.00, 1064.00, 'INR', 1),
  (930003, 920001, 'logged_in', 1092.50, 'cash', 0.00, 12.00, 131.10, 1223.60, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940001, 920001, 'b2b', 10, 19, 730.00, 'INR', 1),
  (940002, 920001, 'b2b', 20, 49, 678.00, 'INR', 1),
  (940003, 920001, 'b2b', 50, NULL, 635.00, 'INR', 1);

-- Product: ALBUMIN (BICC-003)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910002, 1, NULL, 'albumin-bicc-003', 'BIO-BICC', 1, 'BIO-BICC-003', 'Albumin', 'Biogenix', 'Clinical chemistry reagent kit for albumin measurement.', 'Albumin reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920002, 910002, 'BIO-BICC-003-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930004, 920002, 'public', 116.00, 'cash', 0.00, 12.00, 13.92, 129.92, 'INR', 1),
  (930005, 920002, 'retail', 116.00, 'cash', 0.00, 12.00, 13.92, 129.92, 'INR', 1),
  (930006, 920002, 'logged_in', 133.40, 'cash', 0.00, 12.00, 16.01, 149.41, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940004, 920002, 'b2b', 10, 19, 95.00, 'INR', 1),
  (940005, 920002, 'b2b', 20, 49, 90.00, 'INR', 1),
  (940006, 920002, 'b2b', 50, NULL, 82.00, 'INR', 1);

-- Product: ALKALINE PHOSPHATASE (BICC-004)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910003, 1, NULL, 'alkaline-phosphatase-bicc-004', 'BIO-BICC', 1, 'BIO-BICC-004', 'Alkaline Phosphatase', 'Biogenix', 'Clinical chemistry reagent kit for alkaline phosphatase measurement.', 'Alkaline Phosphatase reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920003, 910003, 'BIO-BICC-004-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930007, 920003, 'public', 395.00, 'cash', 0.00, 12.00, 47.40, 442.40, 'INR', 1),
  (930008, 920003, 'retail', 395.00, 'cash', 0.00, 12.00, 47.40, 442.40, 'INR', 1),
  (930009, 920003, 'logged_in', 454.25, 'cash', 0.00, 12.00, 54.51, 508.76, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940007, 920003, 'b2b', 10, 19, 303.00, 'INR', 1),
  (940008, 920003, 'b2b', 20, 49, 290.00, 'INR', 1),
  (940009, 920003, 'b2b', 50, NULL, 275.00, 'INR', 1);

-- Product: AMYLASE (BICC-005)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910004, 1, NULL, 'amylase-bicc-005', 'BIO-BICC', 1, 'BIO-BICC-005', 'Amylase', 'Biogenix', 'Clinical chemistry reagent kit for amylase measurement.', 'Amylase reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920004, 910004, 'BIO-BICC-005-10ML', '10 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930010, 920004, 'public', 295.00, 'cash', 0.00, 12.00, 35.40, 330.40, 'INR', 1),
  (930011, 920004, 'retail', 295.00, 'cash', 0.00, 12.00, 35.40, 330.40, 'INR', 1),
  (930012, 920004, 'logged_in', 339.25, 'cash', 0.00, 12.00, 40.71, 379.96, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940010, 920004, 'b2b', 10, 19, 275.00, 'INR', 1),
  (940011, 920004, 'b2b', 20, 49, 250.00, 'INR', 1),
  (940012, 920004, 'b2b', 50, NULL, 225.00, 'INR', 1);

-- Product: BILIRUBIN TOTAL & DIRECT (BICC-007)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910005, 1, NULL, 'bilirubin-total-direct-bicc-007', 'BIO-BICC', 1, 'BIO-BICC-007', 'Bilirubin Total & Direct', 'Biogenix', 'Clinical chemistry reagent kit for bilirubin total & direct measurement.', 'Bilirubin Total & Direct reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920005, 910005, 'BIO-BICC-007-2X50ML', '2 x 50 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930013, 920005, 'public', 195.00, 'cash', 0.00, 12.00, 23.40, 218.40, 'INR', 1),
  (930014, 920005, 'retail', 195.00, 'cash', 0.00, 12.00, 23.40, 218.40, 'INR', 1),
  (930015, 920005, 'logged_in', 224.25, 'cash', 0.00, 12.00, 26.91, 251.16, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940013, 920005, 'b2b', 10, 19, 160.00, 'INR', 1),
  (940014, 920005, 'b2b', 20, 49, 145.00, 'INR', 1),
  (940015, 920005, 'b2b', 50, NULL, 130.00, 'INR', 1);

-- Product: CALCIUM (BICC-008)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910006, 1, NULL, 'calcium-bicc-008', 'BIO-BICC', 1, 'BIO-BICC-008', 'Calcium', 'Biogenix', 'Clinical chemistry reagent kit for calcium measurement.', 'Calcium reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920006, 910006, 'BIO-BICC-008-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930016, 920006, 'public', 395.00, 'cash', 0.00, 12.00, 47.40, 442.40, 'INR', 1),
  (930017, 920006, 'retail', 395.00, 'cash', 0.00, 12.00, 47.40, 442.40, 'INR', 1),
  (930018, 920006, 'logged_in', 454.25, 'cash', 0.00, 12.00, 54.51, 508.76, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940016, 920006, 'b2b', 10, 19, 303.00, 'INR', 1),
  (940017, 920006, 'b2b', 20, 49, 282.00, 'INR', 1),
  (940018, 920006, 'b2b', 50, NULL, 265.00, 'INR', 1);

-- Product: CHLORIDE (BICC-009)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910007, 1, NULL, 'chloride-bicc-009', 'BIO-BICC', 1, 'BIO-BICC-009', 'Chloride', 'Biogenix', 'Clinical chemistry reagent kit for chloride measurement.', 'Chloride reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920007, 910007, 'BIO-BICC-009-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930019, 920007, 'public', 365.00, 'cash', 0.00, 12.00, 43.80, 408.80, 'INR', 1),
  (930020, 920007, 'retail', 365.00, 'cash', 0.00, 12.00, 43.80, 408.80, 'INR', 1),
  (930021, 920007, 'logged_in', 419.75, 'cash', 0.00, 12.00, 50.37, 470.12, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940019, 920007, 'b2b', 10, 19, 280.00, 'INR', 1),
  (940020, 920007, 'b2b', 20, 49, 260.00, 'INR', 1),
  (940021, 920007, 'b2b', 50, NULL, 240.00, 'INR', 1);

-- Product: HDL CHOLESTEROL PPT (BICC-010)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910008, 1, NULL, 'hdl-cholesterol-ppt-bicc-010', 'BIO-BICC', 1, 'BIO-BICC-010', 'HDL Cholesterol PPT', 'Biogenix', 'Clinical chemistry reagent kit for HDL cholesterol PPT measurement.', 'HDL Cholesterol PPT reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920008, 910008, 'BIO-BICC-010-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930022, 920008, 'public', 390.00, 'cash', 0.00, 12.00, 46.80, 436.80, 'INR', 1),
  (930023, 920008, 'retail', 390.00, 'cash', 0.00, 12.00, 46.80, 436.80, 'INR', 1),
  (930024, 920008, 'logged_in', 448.50, 'cash', 0.00, 12.00, 53.82, 502.32, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940022, 920008, 'b2b', 10, 19, 350.00, 'INR', 1),
  (940023, 920008, 'b2b', 20, 49, 300.00, 'INR', 1),
  (940024, 920008, 'b2b', 50, NULL, 260.00, 'INR', 1);

-- Product: HDL DIRECT CHOLESTEROL (BICC-011)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910009, 1, NULL, 'hdl-direct-cholesterol-bicc-011', 'BIO-BICC', 1, 'BIO-BICC-011', 'HDL Direct Cholesterol', 'Biogenix', 'Clinical chemistry reagent kit for HDL direct cholesterol measurement.', 'HDL Direct Cholesterol reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920009, 910009, 'BIO-BICC-011-40ML', '40 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930025, 920009, 'public', 1095.00, 'cash', 0.00, 12.00, 131.40, 1226.40, 'INR', 1),
  (930026, 920009, 'retail', 1095.00, 'cash', 0.00, 12.00, 131.40, 1226.40, 'INR', 1),
  (930027, 920009, 'logged_in', 1259.25, 'cash', 0.00, 12.00, 151.11, 1410.36, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940025, 920009, 'b2b', 10, 19, 850.00, 'INR', 1),
  (940026, 920009, 'b2b', 20, 49, 785.00, 'INR', 1),
  (940027, 920009, 'b2b', 50, NULL, 730.00, 'INR', 1);

-- Product: LDL DIRECT CHOLESTEROL (BICC-013)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910010, 1, NULL, 'ldl-direct-cholesterol-bicc-013', 'BIO-BICC', 1, 'BIO-BICC-013', 'LDL Direct Cholesterol', 'Biogenix', 'Clinical chemistry reagent kit for LDL direct cholesterol measurement.', 'LDL Direct Cholesterol reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920010, 910010, 'BIO-BICC-013-40ML', '40 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930028, 920010, 'public', 1595.00, 'cash', 0.00, 12.00, 191.40, 1786.40, 'INR', 1),
  (930029, 920010, 'retail', 1595.00, 'cash', 0.00, 12.00, 191.40, 1786.40, 'INR', 1),
  (930030, 920010, 'logged_in', 1834.25, 'cash', 0.00, 12.00, 220.11, 2054.36, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940028, 920010, 'b2b', 10, 19, 1250.00, 'INR', 1),
  (940029, 920010, 'b2b', 20, 49, 1150.00, 'INR', 1),
  (940030, 920010, 'b2b', 50, NULL, 1050.00, 'INR', 1);

-- Product: CHOLESTEROL TOTAL (BICC-014)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910011, 1, NULL, 'cholesterol-total-bicc-014', 'BIO-BICC', 1, 'BIO-BICC-014', 'Cholesterol Total', 'Biogenix', 'Clinical chemistry reagent kit for total cholesterol measurement.', 'Cholesterol Total reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920011, 910011, 'BIO-BICC-014-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930031, 920011, 'public', 450.00, 'cash', 0.00, 12.00, 54.00, 504.00, 'INR', 1),
  (930032, 920011, 'retail', 450.00, 'cash', 0.00, 12.00, 54.00, 504.00, 'INR', 1),
  (930033, 920011, 'logged_in', 517.50, 'cash', 0.00, 12.00, 62.10, 579.60, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940031, 920011, 'b2b', 10, 19, 350.00, 'INR', 1),
  (940032, 920011, 'b2b', 20, 49, 325.00, 'INR', 1),
  (940033, 920011, 'b2b', 50, NULL, 300.00, 'INR', 1);

-- Product: CHOLINESTERASE KIT (BICC-015)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910012, 1, NULL, 'cholinesterase-kit-bicc-015', 'BIO-BICC', 1, 'BIO-BICC-015', 'Cholinesterase Kit', 'Biogenix', 'Clinical chemistry reagent kit for cholinesterase measurement.', 'Cholinesterase reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920012, 910012, 'BIO-BICC-015-48ML', '48 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930034, 920012, 'public', 9500.00, 'cash', 0.00, 12.00, 1140.00, 10640.00, 'INR', 1),
  (930035, 920012, 'retail', 9500.00, 'cash', 0.00, 12.00, 1140.00, 10640.00, 'INR', 1),
  (930036, 920012, 'logged_in', 10925.00, 'cash', 0.00, 12.00, 1311.00, 12236.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940034, 920012, 'b2b', 10, 19, 8450.00, 'INR', 1),
  (940035, 920012, 'b2b', 20, 49, 7650.00, 'INR', 1),
  (940036, 920012, 'b2b', 50, NULL, 6950.00, 'INR', 1);

-- Product: CK-MB (BICC-017)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910013, 1, NULL, 'ck-mb-bicc-017', 'BIO-BICC', 1, 'BIO-BICC-017', 'CK-MB', 'Biogenix', 'Clinical chemistry reagent kit for CK-MB measurement.', 'CK-MB reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920013, 910013, 'BIO-BICC-017-10ML', '10 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930037, 920013, 'public', 550.00, 'cash', 0.00, 12.00, 66.00, 616.00, 'INR', 1),
  (930038, 920013, 'retail', 550.00, 'cash', 0.00, 12.00, 66.00, 616.00, 'INR', 1),
  (930039, 920013, 'logged_in', 632.50, 'cash', 0.00, 12.00, 75.90, 708.40, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940037, 920013, 'b2b', 10, 19, 425.00, 'INR', 1),
  (940038, 920013, 'b2b', 20, 49, 395.00, 'INR', 1),
  (940039, 920013, 'b2b', 50, NULL, 365.00, 'INR', 1);

-- Product: CK NAC (BICC-018)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910014, 1, NULL, 'ck-nac-bicc-018', 'BIO-BICC', 1, 'BIO-BICC-018', 'CK NAC', 'Biogenix', 'Clinical chemistry reagent kit for CK NAC measurement.', 'CK NAC reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920014, 910014, 'BIO-BICC-018-10ML', '10 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930040, 920014, 'public', 450.00, 'cash', 0.00, 12.00, 54.00, 504.00, 'INR', 1),
  (930041, 920014, 'retail', 450.00, 'cash', 0.00, 12.00, 54.00, 504.00, 'INR', 1),
  (930042, 920014, 'logged_in', 517.50, 'cash', 0.00, 12.00, 62.10, 579.60, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940040, 920014, 'b2b', 10, 19, 350.00, 'INR', 1),
  (940041, 920014, 'b2b', 20, 49, 325.00, 'INR', 1),
  (940042, 920014, 'b2b', 50, NULL, 300.00, 'INR', 1);

-- Product: COPPER (BICC-019)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910015, 1, NULL, 'copper-bicc-019', 'BIO-BICC', 1, 'BIO-BICC-019', 'Copper', 'Biogenix', 'Clinical chemistry reagent kit for copper measurement.', 'Copper reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920015, 910015, 'BIO-BICC-019-40ML', '40 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930043, 920015, 'public', 11500.00, 'cash', 0.00, 12.00, 1380.00, 12880.00, 'INR', 1),
  (930044, 920015, 'retail', 11500.00, 'cash', 0.00, 12.00, 1380.00, 12880.00, 'INR', 1),
  (930045, 920015, 'logged_in', 13225.00, 'cash', 0.00, 12.00, 1587.00, 14812.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940043, 920015, 'b2b', 10, 19, 9250.00, 'INR', 1),
  (940044, 920015, 'b2b', 20, 49, 8250.00, 'INR', 1),
  (940045, 920015, 'b2b', 50, NULL, 7850.00, 'INR', 1);

-- Product: CREATININE (BICC-020)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910016, 1, NULL, 'creatinine-bicc-020', 'BIO-BICC', 1, 'BIO-BICC-020', 'Creatinine', 'Biogenix', 'Clinical chemistry reagent kit for creatinine measurement.', 'Creatinine reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920016, 910016, 'BIO-BICC-020-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930046, 920016, 'public', 165.00, 'cash', 0.00, 12.00, 19.80, 184.80, 'INR', 1),
  (930047, 920016, 'retail', 165.00, 'cash', 0.00, 12.00, 19.80, 184.80, 'INR', 1),
  (930048, 920016, 'logged_in', 189.75, 'cash', 0.00, 12.00, 22.77, 212.52, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940046, 920016, 'b2b', 10, 19, 150.00, 'INR', 1),
  (940047, 920016, 'b2b', 20, 49, 125.00, 'INR', 1),
  (940048, 920016, 'b2b', 50, NULL, 110.00, 'INR', 1);

-- Product: FRUCTOSAMINE (BICC-021)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910017, 1, NULL, 'fructosamine-bicc-021', 'BIO-BICC', 1, 'BIO-BICC-021', 'Fructosamine', 'Biogenix', 'Clinical chemistry reagent kit for fructosamine measurement.', 'Fructosamine reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920017, 910017, 'BIO-BICC-021-40ML', '40 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930049, 920017, 'public', 4500.00, 'cash', 0.00, 12.00, 540.00, 5040.00, 'INR', 1),
  (930050, 920017, 'retail', 4500.00, 'cash', 0.00, 12.00, 540.00, 5040.00, 'INR', 1),
  (930051, 920017, 'logged_in', 5175.00, 'cash', 0.00, 12.00, 621.00, 5796.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940049, 920017, 'b2b', 10, 19, 3850.00, 'INR', 1),
  (940050, 920017, 'b2b', 20, 49, 3250.00, 'INR', 1),
  (940051, 920017, 'b2b', 50, NULL, 2850.00, 'INR', 1);

-- Product: GAMMA GT (BICC-022)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910018, 1, NULL, 'gamma-gt-bicc-022', 'BIO-BICC', 1, 'BIO-BICC-022', 'Gamma GT', 'Biogenix', 'Clinical chemistry reagent kit for gamma GT measurement.', 'Gamma GT reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920018, 910018, 'BIO-BICC-022-50ML', '50 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930052, 920018, 'public', 775.00, 'cash', 0.00, 12.00, 93.00, 868.00, 'INR', 1),
  (930053, 920018, 'retail', 775.00, 'cash', 0.00, 12.00, 93.00, 868.00, 'INR', 1),
  (930054, 920018, 'logged_in', 891.25, 'cash', 0.00, 12.00, 106.95, 998.20, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940052, 920018, 'b2b', 10, 19, 615.00, 'INR', 1),
  (940053, 920018, 'b2b', 20, 49, 550.00, 'INR', 1),
  (940054, 920018, 'b2b', 50, NULL, 512.00, 'INR', 1);

-- Product: G6PD (BICC-024)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910019, 1, NULL, 'g6pd-bicc-024', 'BIO-BICC', 1, 'BIO-BICC-024', 'G6PD', 'Biogenix', 'Clinical chemistry reagent kit for G6PD measurement.', 'G6PD reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920019, 910019, 'BIO-BICC-024-40ML', '40 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930055, 920019, 'public', 3250.00, 'cash', 0.00, 12.00, 390.00, 3640.00, 'INR', 1),
  (930056, 920019, 'retail', 3250.00, 'cash', 0.00, 12.00, 390.00, 3640.00, 'INR', 1),
  (930057, 920019, 'logged_in', 3737.50, 'cash', 0.00, 12.00, 448.50, 4186.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940055, 920019, 'b2b', 10, 19, 2850.00, 'INR', 1),
  (940056, 920019, 'b2b', 20, 49, 2550.00, 'INR', 1),
  (940057, 920019, 'b2b', 50, NULL, 2350.00, 'INR', 1);

-- Product: HbA1C (BICC-025)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910020, 1, NULL, 'hba1c-bicc-025', 'BIO-BICC', 1, 'BIO-BICC-025', 'HbA1C', 'Biogenix', 'Clinical chemistry reagent kit for HbA1C measurement.', 'HbA1C reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920020, 910020, 'BIO-BICC-025-40ML', '40 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930058, 920020, 'public', 6600.00, 'cash', 0.00, 12.00, 792.00, 7392.00, 'INR', 1),
  (930059, 920020, 'retail', 6600.00, 'cash', 0.00, 12.00, 792.00, 7392.00, 'INR', 1),
  (930060, 920020, 'logged_in', 7590.00, 'cash', 0.00, 12.00, 910.80, 8500.80, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940058, 920020, 'b2b', 10, 19, 6000.00, 'INR', 1),
  (940059, 920020, 'b2b', 20, 49, 5500.00, 'INR', 1),
  (940060, 920020, 'b2b', 50, NULL, 4850.00, 'INR', 1);

-- Product: IRON (BICC-026)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910021, 1, NULL, 'iron-bicc-026', 'BIO-BICC', 1, 'BIO-BICC-026', 'Iron', 'Biogenix', 'Clinical chemistry reagent kit for iron measurement.', 'Iron reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920021, 910021, 'BIO-BICC-026-25ML', '25 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930061, 920021, 'public', 4500.00, 'cash', 0.00, 12.00, 540.00, 5040.00, 'INR', 1),
  (930062, 920021, 'retail', 4500.00, 'cash', 0.00, 12.00, 540.00, 5040.00, 'INR', 1),
  (930063, 920021, 'logged_in', 5175.00, 'cash', 0.00, 12.00, 621.00, 5796.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940061, 920021, 'b2b', 10, 19, 4250.00, 'INR', 1),
  (940062, 920021, 'b2b', 20, 49, 3850.00, 'INR', 1),
  (940063, 920021, 'b2b', 50, NULL, 3450.00, 'INR', 1);

-- Product: LACTATE (BICC-027)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910022, 1, NULL, 'lactate-bicc-027', 'BIO-BICC', 1, 'BIO-BICC-027', 'Lactate', 'Biogenix', 'Clinical chemistry reagent kit for lactate measurement.', 'Lactate reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920022, 910022, 'BIO-BICC-027-20ML', '20 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930064, 920022, 'public', 10500.00, 'cash', 0.00, 12.00, 1260.00, 11760.00, 'INR', 1),
  (930065, 920022, 'retail', 10500.00, 'cash', 0.00, 12.00, 1260.00, 11760.00, 'INR', 1),
  (930066, 920022, 'logged_in', 12075.00, 'cash', 0.00, 12.00, 1449.00, 13524.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940064, 920022, 'b2b', 10, 19, 10150.00, 'INR', 1),
  (940065, 920022, 'b2b', 20, 49, 9850.00, 'INR', 1),
  (940066, 920022, 'b2b', 50, NULL, 9450.00, 'INR', 1);

-- Product: LACTATE DEHYDROGENASE-P (BICC-028)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910023, 1, NULL, 'lactate-dehydrogenase-p-bicc-028', 'BIO-BICC', 1, 'BIO-BICC-028', 'Lactate Dehydrogenase-P', 'Biogenix', 'Clinical chemistry reagent kit for lactate dehydrogenase-P measurement.', 'Lactate Dehydrogenase-P reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920023, 910023, 'BIO-BICC-028-50ML', '50 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930067, 920023, 'public', 650.00, 'cash', 0.00, 12.00, 78.00, 728.00, 'INR', 1),
  (930068, 920023, 'retail', 650.00, 'cash', 0.00, 12.00, 78.00, 728.00, 'INR', 1),
  (930069, 920023, 'logged_in', 747.50, 'cash', 0.00, 12.00, 89.70, 837.20, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940067, 920023, 'b2b', 10, 19, 525.00, 'INR', 1),
  (940068, 920023, 'b2b', 20, 49, 475.00, 'INR', 1),
  (940069, 920023, 'b2b', 50, NULL, 450.00, 'INR', 1);

-- Product: LIPASE with Calibrator (BICC-029)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910024, 1, NULL, 'lipase-with-calibrator-bicc-029', 'BIO-BICC', 1, 'BIO-BICC-029', 'Lipase with Calibrator', 'Biogenix', 'Clinical chemistry reagent kit for lipase measurement with calibrator.', 'Lipase with Calibrator reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920024, 910024, 'BIO-BICC-029-40ML', '40 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930070, 920024, 'public', 2800.00, 'cash', 0.00, 12.00, 336.00, 3136.00, 'INR', 1),
  (930071, 920024, 'retail', 2800.00, 'cash', 0.00, 12.00, 336.00, 3136.00, 'INR', 1),
  (930072, 920024, 'logged_in', 3220.00, 'cash', 0.00, 12.00, 386.40, 3606.40, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940070, 920024, 'b2b', 10, 19, 2450.00, 'INR', 1),
  (940071, 920024, 'b2b', 20, 49, 2250.00, 'INR', 1),
  (940072, 920024, 'b2b', 50, NULL, 1950.00, 'INR', 1);

-- Product: MAGNESIUM (BICC-030)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910025, 1, NULL, 'magnesium-bicc-030', 'BIO-BICC', 1, 'BIO-BICC-030', 'Magnesium', 'Biogenix', 'Clinical chemistry reagent kit for magnesium measurement.', 'Magnesium reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920025, 910025, 'BIO-BICC-030-50ML', '50 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930073, 920025, 'public', 450.00, 'cash', 0.00, 12.00, 54.00, 504.00, 'INR', 1),
  (930074, 920025, 'retail', 450.00, 'cash', 0.00, 12.00, 54.00, 504.00, 'INR', 1),
  (930075, 920025, 'logged_in', 517.50, 'cash', 0.00, 12.00, 62.10, 579.60, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940073, 920025, 'b2b', 10, 19, 375.00, 'INR', 1),
  (940074, 920025, 'b2b', 20, 49, 325.00, 'INR', 1),
  (940075, 920025, 'b2b', 50, NULL, 300.00, 'INR', 1);

-- Product: MICROPROTEIN (BICC-031)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910026, 1, NULL, 'microprotein-bicc-031', 'BIO-BICC', 1, 'BIO-BICC-031', 'Microprotein', 'Biogenix', 'Clinical chemistry reagent kit for microprotein measurement.', 'Microprotein reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920026, 910026, 'BIO-BICC-031-10ML', '10 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930076, 920026, 'public', 450.00, 'cash', 0.00, 12.00, 54.00, 504.00, 'INR', 1),
  (930077, 920026, 'retail', 450.00, 'cash', 0.00, 12.00, 54.00, 504.00, 'INR', 1),
  (930078, 920026, 'logged_in', 517.50, 'cash', 0.00, 12.00, 62.10, 579.60, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940076, 920026, 'b2b', 10, 19, 375.00, 'INR', 1),
  (940077, 920026, 'b2b', 20, 49, 325.00, 'INR', 1),
  (940078, 920026, 'b2b', 50, NULL, 300.00, 'INR', 1);

-- Product: PHOSPHORUS (BICC-032)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910027, 1, NULL, 'phosphorus-bicc-032', 'BIO-BICC', 1, 'BIO-BICC-032', 'Phosphorus', 'Biogenix', 'Clinical chemistry reagent kit for phosphorus measurement.', 'Phosphorus reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920027, 910027, 'BIO-BICC-032-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930079, 920027, 'public', 450.00, 'cash', 0.00, 12.00, 54.00, 504.00, 'INR', 1),
  (930080, 920027, 'retail', 450.00, 'cash', 0.00, 12.00, 54.00, 504.00, 'INR', 1),
  (930081, 920027, 'logged_in', 517.50, 'cash', 0.00, 12.00, 62.10, 579.60, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940079, 920027, 'b2b', 10, 19, 375.00, 'INR', 1),
  (940080, 920027, 'b2b', 20, 49, 325.00, 'INR', 1),
  (940081, 920027, 'b2b', 50, NULL, 300.00, 'INR', 1);

-- Product: POTASSIUM (BICC-033)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910028, 1, NULL, 'potassium-bicc-033', 'BIO-BICC', 1, 'BIO-BICC-033', 'Potassium', 'Biogenix', 'Clinical chemistry reagent kit for potassium measurement.', 'Potassium reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920028, 910028, 'BIO-BICC-033-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930082, 920028, 'public', 750.00, 'cash', 0.00, 12.00, 90.00, 840.00, 'INR', 1),
  (930083, 920028, 'retail', 750.00, 'cash', 0.00, 12.00, 90.00, 840.00, 'INR', 1),
  (930084, 920028, 'logged_in', 862.50, 'cash', 0.00, 12.00, 103.50, 966.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940082, 920028, 'b2b', 10, 19, 675.00, 'INR', 1),
  (940083, 920028, 'b2b', 20, 49, 525.00, 'INR', 1),
  (940084, 920028, 'b2b', 50, NULL, 495.00, 'INR', 1);

-- Product: SODIUM (BICC-034)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910029, 1, NULL, 'sodium-bicc-034', 'BIO-BICC', 1, 'BIO-BICC-034', 'Sodium', 'Biogenix', 'Clinical chemistry reagent kit for sodium measurement.', 'Sodium reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920029, 910029, 'BIO-BICC-034-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930085, 920029, 'public', 750.00, 'cash', 0.00, 12.00, 90.00, 840.00, 'INR', 1),
  (930086, 920029, 'retail', 750.00, 'cash', 0.00, 12.00, 90.00, 840.00, 'INR', 1),
  (930087, 920029, 'logged_in', 862.50, 'cash', 0.00, 12.00, 103.50, 966.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940085, 920029, 'b2b', 10, 19, 675.00, 'INR', 1),
  (940086, 920029, 'b2b', 20, 49, 525.00, 'INR', 1),
  (940087, 920029, 'b2b', 50, NULL, 495.00, 'INR', 1);

-- Product: SGPT (BICC-035)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910030, 1, NULL, 'sgpt-bicc-035', 'BIO-BICC', 1, 'BIO-BICC-035', 'SGPT', 'Biogenix', 'Clinical chemistry reagent kit for SGPT measurement.', 'SGPT reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920030, 910030, 'BIO-BICC-035-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930088, 920030, 'public', 380.00, 'cash', 0.00, 12.00, 45.60, 425.60, 'INR', 1),
  (930089, 920030, 'retail', 380.00, 'cash', 0.00, 12.00, 45.60, 425.60, 'INR', 1),
  (930090, 920030, 'logged_in', 437.00, 'cash', 0.00, 12.00, 52.44, 489.44, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940088, 920030, 'b2b', 10, 19, 325.00, 'INR', 1),
  (940089, 920030, 'b2b', 20, 49, 295.00, 'INR', 1),
  (940090, 920030, 'b2b', 50, NULL, 275.00, 'INR', 1);

-- Product: SGOT (BICC-036)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910031, 1, NULL, 'sgot-bicc-036', 'BIO-BICC', 1, 'BIO-BICC-036', 'SGOT', 'Biogenix', 'Clinical chemistry reagent kit for SGOT measurement.', 'SGOT reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920031, 910031, 'BIO-BICC-036-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930091, 920031, 'public', 380.00, 'cash', 0.00, 12.00, 45.60, 425.60, 'INR', 1),
  (930092, 920031, 'retail', 380.00, 'cash', 0.00, 12.00, 45.60, 425.60, 'INR', 1),
  (930093, 920031, 'logged_in', 437.00, 'cash', 0.00, 12.00, 52.44, 489.44, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940091, 920031, 'b2b', 10, 19, 325.00, 'INR', 1),
  (940092, 920031, 'b2b', 20, 49, 295.00, 'INR', 1),
  (940093, 920031, 'b2b', 50, NULL, 275.00, 'INR', 1);

-- Product: TOTAL PROTEIN (BICC-037)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910032, 1, NULL, 'total-protein-bicc-037', 'BIO-BICC', 1, 'BIO-BICC-037', 'Total Protein', 'Biogenix', 'Clinical chemistry reagent kit for total protein measurement.', 'Total Protein reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920032, 910032, 'BIO-BICC-037-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930094, 920032, 'public', 145.00, 'cash', 0.00, 12.00, 17.40, 162.40, 'INR', 1),
  (930095, 920032, 'retail', 145.00, 'cash', 0.00, 12.00, 17.40, 162.40, 'INR', 1),
  (930096, 920032, 'logged_in', 166.75, 'cash', 0.00, 12.00, 20.01, 186.76, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940094, 920032, 'b2b', 10, 19, 115.00, 'INR', 1),
  (940095, 920032, 'b2b', 20, 49, 108.00, 'INR', 1),
  (940096, 920032, 'b2b', 50, NULL, 98.00, 'INR', 1);

-- Product: TRIGLYCERIDES (BICC-039)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910033, 1, NULL, 'triglycerides-bicc-039', 'BIO-BICC', 1, 'BIO-BICC-039', 'Triglycerides', 'Biogenix', 'Clinical chemistry reagent kit for triglycerides measurement.', 'Triglycerides reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920033, 910033, 'BIO-BICC-039-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930097, 920033, 'public', 795.00, 'cash', 0.00, 12.00, 95.40, 890.40, 'INR', 1),
  (930098, 920033, 'retail', 795.00, 'cash', 0.00, 12.00, 95.40, 890.40, 'INR', 1),
  (930099, 920033, 'logged_in', 914.25, 'cash', 0.00, 12.00, 109.71, 1023.96, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940097, 920033, 'b2b', 10, 19, 725.00, 'INR', 1),
  (940098, 920033, 'b2b', 20, 49, 650.00, 'INR', 1),
  (940099, 920033, 'b2b', 50, NULL, 600.00, 'INR', 1);

-- Product: UREA BERTHELOT (BICC-040)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910034, 1, NULL, 'urea-berthelot-bicc-040', 'BIO-BICC', 1, 'BIO-BICC-040', 'Urea Berthelot', 'Biogenix', 'Clinical chemistry reagent kit for urea berthelot measurement.', 'Urea Berthelot reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920034, 910034, 'BIO-BICC-040-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930100, 920034, 'public', 275.00, 'cash', 0.00, 12.00, 33.00, 308.00, 'INR', 1),
  (930101, 920034, 'retail', 275.00, 'cash', 0.00, 12.00, 33.00, 308.00, 'INR', 1),
  (930102, 920034, 'logged_in', 316.25, 'cash', 0.00, 12.00, 37.95, 354.20, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940100, 920034, 'b2b', 10, 19, 250.00, 'INR', 1),
  (940101, 920034, 'b2b', 20, 49, 210.00, 'INR', 1),
  (940102, 920034, 'b2b', 50, NULL, 195.00, 'INR', 1);

-- Product: UREA UV (BICC-041)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910035, 1, NULL, 'urea-uv-bicc-041', 'BIO-BICC', 1, 'BIO-BICC-041', 'Urea UV', 'Biogenix', 'Clinical chemistry reagent kit for urea UV measurement.', 'Urea UV reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920035, 910035, 'BIO-BICC-041-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930103, 920035, 'public', 350.00, 'cash', 0.00, 12.00, 42.00, 392.00, 'INR', 1),
  (930104, 920035, 'retail', 350.00, 'cash', 0.00, 12.00, 42.00, 392.00, 'INR', 1),
  (930105, 920035, 'logged_in', 402.50, 'cash', 0.00, 12.00, 48.30, 450.80, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940103, 920035, 'b2b', 10, 19, 290.00, 'INR', 1),
  (940104, 920035, 'b2b', 20, 49, 255.00, 'INR', 1),
  (940105, 920035, 'b2b', 50, NULL, 245.00, 'INR', 1);

-- Product: URIC ACID (BICC-042)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910036, 1, NULL, 'uric-acid-bicc-042', 'BIO-BICC', 1, 'BIO-BICC-042', 'Uric Acid', 'Biogenix', 'Clinical chemistry reagent kit for uric acid measurement.', 'Uric Acid reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920036, 910036, 'BIO-BICC-042-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930106, 920036, 'public', 450.00, 'cash', 0.00, 12.00, 54.00, 504.00, 'INR', 1),
  (930107, 920036, 'retail', 450.00, 'cash', 0.00, 12.00, 54.00, 504.00, 'INR', 1),
  (930108, 920036, 'logged_in', 517.50, 'cash', 0.00, 12.00, 62.10, 579.60, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940106, 920036, 'b2b', 10, 19, 375.00, 'INR', 1),
  (940107, 920036, 'b2b', 20, 49, 325.00, 'INR', 1),
  (940108, 920036, 'b2b', 50, NULL, 300.00, 'INR', 1);

-- Product: ZINC (BICC-043)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910037, 1, NULL, 'zinc-bicc-043', 'BIO-BICC', 1, 'BIO-BICC-043', 'Zinc', 'Biogenix', 'Clinical chemistry reagent kit for zinc measurement.', 'Zinc reagent kit for clinical chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920037, 910037, 'BIO-BICC-043-50ML', '50 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930109, 920037, 'public', 6500.00, 'cash', 0.00, 12.00, 780.00, 7280.00, 'INR', 1),
  (930110, 920037, 'retail', 6500.00, 'cash', 0.00, 12.00, 780.00, 7280.00, 'INR', 1),
  (930111, 920037, 'logged_in', 7475.00, 'cash', 0.00, 12.00, 897.00, 8372.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940109, 920037, 'b2b', 10, 19, 5000.00, 'INR', 1),
  (940110, 920037, 'b2b', 20, 49, 4650.00, 'INR', 1),
  (940111, 920037, 'b2b', 50, NULL, 4250.00, 'INR', 1);


-- ============================================================
-- SECTION 2: SPECIAL CHEMISTRY (TURBIDIMETRY)
-- ============================================================

-- Product: ASO TURBILATEX (BIT-001)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910038, 10, NULL, 'aso-turbilatex-bit-001', 'BIO-BIT', 1, 'BIO-BIT-001', 'ASO Turbilatex', 'Biogenix', 'Special chemistry reagent kit for ASO turbidimetric measurement.', 'ASO Turbilatex reagent kit for special chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920038, 910038, 'BIO-BIT-001-50ML', '50 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930112, 920038, 'public', 1850.00, 'cash', 0.00, 12.00, 222.00, 2072.00, 'INR', 1),
  (930113, 920038, 'retail', 1850.00, 'cash', 0.00, 12.00, 222.00, 2072.00, 'INR', 1),
  (930114, 920038, 'logged_in', 2127.50, 'cash', 0.00, 12.00, 255.30, 2382.80, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940112, 920038, 'b2b', 10, 19, 1650.00, 'INR', 1),
  (940113, 920038, 'b2b', 20, 49, 1475.00, 'INR', 1),
  (940114, 920038, 'b2b', 50, NULL, 1320.00, 'INR', 1);

-- Product: CRP TURBILATEX (BIT-002)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910039, 10, NULL, 'crp-turbilatex-bit-002', 'BIO-BIT', 1, 'BIO-BIT-002', 'CRP Turbilatex', 'Biogenix', 'Special chemistry reagent kit for CRP turbidimetric measurement.', 'CRP Turbilatex reagent kit for special chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920039, 910039, 'BIO-BIT-002-50ML', '50 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930115, 920039, 'public', 1250.00, 'cash', 0.00, 12.00, 150.00, 1400.00, 'INR', 1),
  (930116, 920039, 'retail', 1250.00, 'cash', 0.00, 12.00, 150.00, 1400.00, 'INR', 1),
  (930117, 920039, 'logged_in', 1437.50, 'cash', 0.00, 12.00, 172.50, 1610.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940115, 920039, 'b2b', 10, 19, 1095.00, 'INR', 1),
  (940116, 920039, 'b2b', 20, 49, 915.00, 'INR', 1),
  (940117, 920039, 'b2b', 50, NULL, 850.00, 'INR', 1);

-- Product: RF TURBILATEX (BIT-003)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910040, 10, NULL, 'rf-turbilatex-bit-003', 'BIO-BIT', 1, 'BIO-BIT-003', 'RF Turbilatex', 'Biogenix', 'Special chemistry reagent kit for RF turbidimetric measurement.', 'RF Turbilatex reagent kit for special chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920040, 910040, 'BIO-BIT-003-50ML', '50 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930118, 920040, 'public', 1450.00, 'cash', 0.00, 12.00, 174.00, 1624.00, 'INR', 1),
  (930119, 920040, 'retail', 1450.00, 'cash', 0.00, 12.00, 174.00, 1624.00, 'INR', 1),
  (930120, 920040, 'logged_in', 1667.50, 'cash', 0.00, 12.00, 200.10, 1867.60, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940118, 920040, 'b2b', 10, 19, 1250.00, 'INR', 1),
  (940119, 920040, 'b2b', 20, 49, 1050.00, 'INR', 1),
  (940120, 920040, 'b2b', 50, NULL, 985.00, 'INR', 1);

-- Product: D-DIMER TURBIDIMETRY (BIT-004)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910041, 10, NULL, 'd-dimer-turbidimetry-bit-004', 'BIO-BIT', 1, 'BIO-BIT-004', 'D-Dimer Turbidimetry', 'Biogenix', 'Special chemistry reagent kit for D-dimer turbidimetric measurement.', 'D-Dimer Turbidimetry reagent kit for special chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920041, 910041, 'BIO-BIT-004-40ML', '40 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930121, 920041, 'public', 6500.00, 'cash', 0.00, 12.00, 780.00, 7280.00, 'INR', 1),
  (930122, 920041, 'retail', 6500.00, 'cash', 0.00, 12.00, 780.00, 7280.00, 'INR', 1),
  (930123, 920041, 'logged_in', 7475.00, 'cash', 0.00, 12.00, 897.00, 8372.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940121, 920041, 'b2b', 10, 19, 5850.00, 'INR', 1),
  (940122, 920041, 'b2b', 20, 49, 4650.00, 'INR', 1),
  (940123, 920041, 'b2b', 50, NULL, 4250.00, 'INR', 1);

-- Product: FERRITIN TURBILATEX (BIT-005)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910042, 10, NULL, 'ferritin-turbilatex-bit-005', 'BIO-BIT', 1, 'BIO-BIT-005', 'Ferritin Turbilatex', 'Biogenix', 'Special chemistry reagent kit for ferritin turbidimetric measurement.', 'Ferritin Turbilatex reagent kit for special chemistry analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920042, 910042, 'BIO-BIT-005-40ML', '40 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930124, 920042, 'public', 5500.00, 'cash', 0.00, 12.00, 660.00, 6160.00, 'INR', 1),
  (930125, 920042, 'retail', 5500.00, 'cash', 0.00, 12.00, 660.00, 6160.00, 'INR', 1),
  (930126, 920042, 'logged_in', 6325.00, 'cash', 0.00, 12.00, 759.00, 7084.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940124, 920042, 'b2b', 10, 19, 4850.00, 'INR', 1),
  (940125, 920042, 'b2b', 20, 49, 4150.00, 'INR', 1),
  (940126, 920042, 'b2b', 50, NULL, 3850.00, 'INR', 1);


-- ============================================================
-- SECTION 3: RAPID TESTS
-- ============================================================

-- Product: DENGUE NS1 Ag RAPID TEST (BIR-003)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910043, 7, NULL, 'dengue-ns1-ag-rapid-test-bir-003', 'BIO-BIR', 1, 'BIO-BIR-003', 'Dengue NS1 Ag Rapid Test', 'Biogenix', 'Rapid test for dengue NS1 antigen detection.', 'Dengue NS1 Ag Rapid Test for quick dengue screening.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920043, 910043, 'BIO-BIR-003-50T', '50 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930127, 920043, 'public', 40.00, 'cash', 0.00, 12.00, 4.80, 44.80, 'INR', 1),
  (930128, 920043, 'retail', 40.00, 'cash', 0.00, 12.00, 4.80, 44.80, 'INR', 1),
  (930129, 920043, 'logged_in', 46.00, 'cash', 0.00, 12.00, 5.52, 51.52, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940127, 920043, 'b2b', 10000, 24999, 25.00, 'INR', 1),
  (940128, 920043, 'b2b', 25000, 49999, 22.50, 'INR', 1),
  (940129, 920043, 'b2b', 50000, 99999, 22.00, 'INR', 1),
  (940130, 920043, 'b2b', 100000, NULL, 20.00, 'INR', 1);

-- Product: DENGUE IgG/IgM Ab RAPID TEST (BIR-004)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910044, 7, NULL, 'dengue-igg-igm-ab-rapid-test-bir-004', 'BIO-BIR', 1, 'BIO-BIR-004', 'Dengue IgG/IgM Ab Rapid Test', 'Biogenix', 'Rapid test for dengue IgG/IgM antibody detection.', 'Dengue IgG/IgM Ab Rapid Test for quick dengue screening.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920044, 910044, 'BIO-BIR-004-40T', '40 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930130, 920044, 'public', 35.00, 'cash', 0.00, 12.00, 4.20, 39.20, 'INR', 1),
  (930131, 920044, 'retail', 35.00, 'cash', 0.00, 12.00, 4.20, 39.20, 'INR', 1),
  (930132, 920044, 'logged_in', 40.25, 'cash', 0.00, 12.00, 4.83, 45.08, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940131, 920044, 'b2b', 10000, 24999, 25.00, 'INR', 1),
  (940132, 920044, 'b2b', 25000, 49999, 22.00, 'INR', 1),
  (940133, 920044, 'b2b', 50000, 99999, 20.00, 'INR', 1),
  (940134, 920044, 'b2b', 100000, NULL, 18.00, 'INR', 1);

-- Product: H. PYLORI Ab RAPID TEST (BIR-009)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910045, 7, NULL, 'h-pylori-ab-rapid-test-bir-009', 'BIO-BIR', 1, 'BIO-BIR-009', 'H. Pylori Ab Rapid Test', 'Biogenix', 'Rapid test for H. pylori antibody detection.', 'H. Pylori Ab Rapid Test for quick H. pylori screening.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920045, 910045, 'BIO-BIR-009-40T', '40 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930133, 920045, 'public', 24.00, 'cash', 0.00, 12.00, 2.88, 26.88, 'INR', 1),
  (930134, 920045, 'retail', 24.00, 'cash', 0.00, 12.00, 2.88, 26.88, 'INR', 1),
  (930135, 920045, 'logged_in', 27.60, 'cash', 0.00, 12.00, 3.31, 30.91, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940135, 920045, 'b2b', 10000, 24999, 22.00, 'INR', 1),
  (940136, 920045, 'b2b', 25000, 49999, 20.00, 'INR', 1),
  (940137, 920045, 'b2b', 50000, 99999, 18.00, 'INR', 1),
  (940138, 920045, 'b2b', 100000, NULL, 16.00, 'INR', 1);

-- Product: H. PYLORI Ag RAPID TEST (BIR-010)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910046, 7, NULL, 'h-pylori-ag-rapid-test-bir-010', 'BIO-BIR', 1, 'BIO-BIR-010', 'H. Pylori Ag Rapid Test', 'Biogenix', 'Rapid test for H. pylori antigen detection.', 'H. Pylori Ag Rapid Test for quick H. pylori screening.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920046, 910046, 'BIO-BIR-010-25T', '25 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930136, 920046, 'public', 39.00, 'cash', 0.00, 12.00, 4.68, 43.68, 'INR', 1),
  (930137, 920046, 'retail', 39.00, 'cash', 0.00, 12.00, 4.68, 43.68, 'INR', 1),
  (930138, 920046, 'logged_in', 44.85, 'cash', 0.00, 12.00, 5.38, 50.23, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940139, 920046, 'b2b', 10000, 24999, 35.00, 'INR', 1),
  (940140, 920046, 'b2b', 25000, 49999, 32.00, 'INR', 1),
  (940141, 920046, 'b2b', 50000, 99999, 30.00, 'INR', 1),
  (940142, 920046, 'b2b', 100000, NULL, 28.00, 'INR', 1);

-- Product: MALARIA Pf/Pv Ag RAPID TEST (BIR-015)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910047, 7, NULL, 'malaria-pf-pv-ag-rapid-test-bir-015', 'BIO-BIR', 1, 'BIO-BIR-015', 'Malaria Pf/Pv Ag Rapid Test', 'Biogenix', 'Rapid test for malaria Pf/Pv antigen detection.', 'Malaria Pf/Pv Ag Rapid Test for quick malaria screening.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920047, 910047, 'BIO-BIR-015-50T', '50 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930139, 920047, 'public', 12.00, 'cash', 0.00, 12.00, 1.44, 13.44, 'INR', 1),
  (930140, 920047, 'retail', 12.00, 'cash', 0.00, 12.00, 1.44, 13.44, 'INR', 1),
  (930141, 920047, 'logged_in', 13.80, 'cash', 0.00, 12.00, 1.66, 15.46, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940143, 920047, 'b2b', 10000, 24999, 9.00, 'INR', 1),
  (940144, 920047, 'b2b', 25000, 49999, 8.50, 'INR', 1),
  (940145, 920047, 'b2b', 50000, 99999, 8.00, 'INR', 1),
  (940146, 920047, 'b2b', 100000, NULL, 7.50, 'INR', 1);

-- Product: MALARIA Pf/Pan Ag RAPID TEST (BIR-016)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910048, 7, NULL, 'malaria-pf-pan-ag-rapid-test-bir-016', 'BIO-BIR', 1, 'BIO-BIR-016', 'Malaria Pf/Pan Ag Rapid Test', 'Biogenix', 'Rapid test for malaria Pf/Pan antigen detection.', 'Malaria Pf/Pan Ag Rapid Test for quick malaria screening.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920048, 910048, 'BIO-BIR-016-50T', '50 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930142, 920048, 'public', 13.00, 'cash', 0.00, 12.00, 1.56, 14.56, 'INR', 1),
  (930143, 920048, 'retail', 13.00, 'cash', 0.00, 12.00, 1.56, 14.56, 'INR', 1),
  (930144, 920048, 'logged_in', 14.95, 'cash', 0.00, 12.00, 1.79, 16.74, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940147, 920048, 'b2b', 10000, 24999, 12.00, 'INR', 1),
  (940148, 920048, 'b2b', 25000, 49999, 10.50, 'INR', 1),
  (940149, 920048, 'b2b', 50000, 99999, 9.50, 'INR', 1),
  (940150, 920048, 'b2b', 100000, NULL, 9.00, 'INR', 1);

-- Product: PREGNANCY (hCG) RAPID TEST MINI (BIR-017m)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910049, 7, NULL, 'pregnancy-hcg-rapid-test-mini-bir-017m', 'BIO-BIR', 1, 'BIO-BIR-017M', 'Pregnancy (hCG) Rapid Test Mini', 'Biogenix', 'Rapid test for pregnancy hCG detection (Mini format).', 'Pregnancy hCG Rapid Test Mini for quick pregnancy detection.', 12.00, 'public', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920049, 910049, 'BIO-BIR-017M-50T', '50 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930145, 920049, 'public', 3.50, 'cash', 0.00, 12.00, 0.42, 3.92, 'INR', 1),
  (930146, 920049, 'retail', 3.50, 'cash', 0.00, 12.00, 0.42, 3.92, 'INR', 1),
  (930147, 920049, 'logged_in', 4.03, 'cash', 0.00, 12.00, 0.48, 4.51, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940151, 920049, 'b2b', 10000, 24999, 3.50, 'INR', 1),
  (940152, 920049, 'b2b', 25000, 49999, 3.50, 'INR', 1),
  (940153, 920049, 'b2b', 50000, 99999, 3.25, 'INR', 1),
  (940154, 920049, 'b2b', 100000, NULL, 3.00, 'INR', 1);

-- Product: SYPHILIS Ab RAPID TEST S/P (BIR-018S)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910050, 7, NULL, 'syphilis-ab-rapid-test-sp-bir-018s', 'BIO-BIR', 1, 'BIO-BIR-018S', 'Syphilis Ab Rapid Test (S/P)', 'Biogenix', 'Rapid test for syphilis antibody detection (serum/plasma).', 'Syphilis Ab Rapid Test for quick syphilis screening.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920050, 910050, 'BIO-BIR-018S-50T', '50 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930148, 920050, 'public', 7.00, 'cash', 0.00, 12.00, 0.84, 7.84, 'INR', 1),
  (930149, 920050, 'retail', 7.00, 'cash', 0.00, 12.00, 0.84, 7.84, 'INR', 1),
  (930150, 920050, 'logged_in', 8.05, 'cash', 0.00, 12.00, 0.97, 9.02, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940155, 920050, 'b2b', 10000, 24999, 6.00, 'INR', 1),
  (940156, 920050, 'b2b', 25000, 49999, 5.50, 'INR', 1),
  (940157, 920050, 'b2b', 50000, 99999, 5.25, 'INR', 1),
  (940158, 920050, 'b2b', 100000, NULL, 4.75, 'INR', 1);

-- Product: HIV 1/2 RAPID TEST (BIR-022)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910051, 7, NULL, 'hiv-1-2-rapid-test-bir-022', 'BIO-BIR', 1, 'BIO-BIR-022', 'HIV 1/2 Rapid Test', 'Biogenix', 'Rapid test for HIV 1/2 detection.', 'HIV 1/2 Rapid Test for quick HIV screening.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920051, 910051, 'BIO-BIR-022-50T', '50 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930151, 920051, 'public', 14.00, 'cash', 0.00, 12.00, 1.68, 15.68, 'INR', 1),
  (930152, 920051, 'retail', 14.00, 'cash', 0.00, 12.00, 1.68, 15.68, 'INR', 1),
  (930153, 920051, 'logged_in', 16.10, 'cash', 0.00, 12.00, 1.93, 18.03, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940159, 920051, 'b2b', 10000, 24999, 11.00, 'INR', 1),
  (940160, 920051, 'b2b', 25000, 49999, 9.50, 'INR', 1),
  (940161, 920051, 'b2b', 50000, 99999, 9.00, 'INR', 1),
  (940162, 920051, 'b2b', 100000, NULL, 8.50, 'INR', 1);

-- Product: HCV RAPID TEST (BIR-023)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910052, 7, NULL, 'hcv-rapid-test-bir-023', 'BIO-BIR', 1, 'BIO-BIR-023', 'HCV Rapid Test', 'Biogenix', 'Rapid test for hepatitis C virus detection.', 'HCV Rapid Test for quick HCV screening.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920052, 910052, 'BIO-BIR-023-50T', '50 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930154, 920052, 'public', 14.00, 'cash', 0.00, 12.00, 1.68, 15.68, 'INR', 1),
  (930155, 920052, 'retail', 14.00, 'cash', 0.00, 12.00, 1.68, 15.68, 'INR', 1),
  (930156, 920052, 'logged_in', 16.10, 'cash', 0.00, 12.00, 1.93, 18.03, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940163, 920052, 'b2b', 10000, 24999, 11.00, 'INR', 1),
  (940164, 920052, 'b2b', 25000, 49999, 9.50, 'INR', 1),
  (940165, 920052, 'b2b', 50000, 99999, 9.00, 'INR', 1),
  (940166, 920052, 'b2b', 100000, NULL, 8.50, 'INR', 1);

-- Product: HBsAg RAPID TEST S/P (BIR-024S)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910053, 7, NULL, 'hbsag-rapid-test-sp-bir-024s', 'BIO-BIR', 1, 'BIO-BIR-024S', 'HBsAg Rapid Test (S/P)', 'Biogenix', 'Rapid test for Hepatitis B surface antigen detection (serum/plasma).', 'HBsAg Rapid Test for quick Hepatitis B screening.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920053, 910053, 'BIO-BIR-024S-50T', '50 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930157, 920053, 'public', 7.00, 'cash', 0.00, 12.00, 0.84, 7.84, 'INR', 1),
  (930158, 920053, 'retail', 7.00, 'cash', 0.00, 12.00, 0.84, 7.84, 'INR', 1),
  (930159, 920053, 'logged_in', 8.05, 'cash', 0.00, 12.00, 0.97, 9.02, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940167, 920053, 'b2b', 10000, 24999, 6.00, 'INR', 1),
  (940168, 920053, 'b2b', 25000, 49999, 5.50, 'INR', 1),
  (940169, 920053, 'b2b', 50000, 99999, 5.25, 'INR', 1),
  (940170, 920053, 'b2b', 100000, NULL, 4.75, 'INR', 1);

-- Product: TYPHOID IgG/IgM RAPID TEST (BIR-021)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910054, 7, NULL, 'typhoid-igg-igm-rapid-test-bir-021', 'BIO-BIR', 1, 'BIO-BIR-021', 'Typhoid IgG/IgM Rapid Test', 'Biogenix', 'Rapid test for typhoid IgG/IgM detection.', 'Typhoid IgG/IgM Rapid Test for quick typhoid screening.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920054, 910054, 'BIO-BIR-021-50T', '50 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930160, 920054, 'public', 20.00, 'cash', 0.00, 12.00, 2.40, 22.40, 'INR', 1),
  (930161, 920054, 'retail', 20.00, 'cash', 0.00, 12.00, 2.40, 22.40, 'INR', 1),
  (930162, 920054, 'logged_in', 23.00, 'cash', 0.00, 12.00, 2.76, 25.76, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940171, 920054, 'b2b', 10000, 24999, 14.00, 'INR', 1),
  (940172, 920054, 'b2b', 25000, 49999, 13.50, 'INR', 1),
  (940173, 920054, 'b2b', 50000, 99999, 12.00, 'INR', 1),
  (940174, 920054, 'b2b', 100000, NULL, 11.00, 'INR', 1);

-- Product: SICKLE CELL RAPID TEST (BIR-034)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910055, 7, NULL, 'sickle-cell-rapid-test-bir-034', 'BIO-BIR', 1, 'BIO-BIR-034', 'Sickle Cell Rapid Test', 'Biogenix', 'Rapid test for sickle cell detection.', 'Sickle Cell Rapid Test for quick sickle cell screening.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920055, 910055, 'BIO-BIR-034-30T', '30 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930163, 920055, 'public', 70.00, 'cash', 0.00, 12.00, 8.40, 78.40, 'INR', 1),
  (930164, 920055, 'retail', 70.00, 'cash', 0.00, 12.00, 8.40, 78.40, 'INR', 1),
  (930165, 920055, 'logged_in', 80.50, 'cash', 0.00, 12.00, 9.66, 90.16, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940175, 920055, 'b2b', 10000, 24999, 55.00, 'INR', 1),
  (940176, 920055, 'b2b', 25000, 49999, 45.00, 'INR', 1),
  (940177, 920055, 'b2b', 50000, 99999, 40.00, 'INR', 1),
  (940178, 920055, 'b2b', 100000, NULL, 35.00, 'INR', 1);


-- ============================================================
-- SECTION 4: URINE STRIPS
-- ============================================================

-- Product: URINE STRIP 2 PARAMETER (BIU-001)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910056, 9, NULL, 'urine-strip-2-parameter-biu-001', 'BIO-BIU', 1, 'BIO-BIU-001', 'Urine Strip 2 Parameter', 'Biogenix', 'Urine test strip for 2 parameter analysis.', 'Urine Strip 2 Parameter for routine urinalysis.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920056, 910056, 'BIO-BIU-001-100T', '100 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930166, 920056, 'public', 50.00, 'cash', 0.00, 12.00, 6.00, 56.00, 'INR', 1),
  (930167, 920056, 'retail', 50.00, 'cash', 0.00, 12.00, 6.00, 56.00, 'INR', 1),
  (930168, 920056, 'logged_in', 57.50, 'cash', 0.00, 12.00, 6.90, 64.40, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940179, 920056, 'b2b', 1000, 1999, 45.00, 'INR', 1),
  (940180, 920056, 'b2b', 2000, 4999, 40.00, 'INR', 1),
  (940181, 920056, 'b2b', 5000, 9999, 35.00, 'INR', 1),
  (940182, 920056, 'b2b', 10000, NULL, 30.00, 'INR', 1);

-- Product: URINE STRIP 10 PARAMETER (BIU-003)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910057, 9, NULL, 'urine-strip-10-parameter-biu-003', 'BIO-BIU', 1, 'BIO-BIU-003', 'Urine Strip 10 Parameter', 'Biogenix', 'Urine test strip for 10 parameter analysis.', 'Urine Strip 10 Parameter for comprehensive urinalysis.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920057, 910057, 'BIO-BIU-003-100T', '100 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930169, 920057, 'public', 330.00, 'cash', 0.00, 12.00, 39.60, 369.60, 'INR', 1),
  (930170, 920057, 'retail', 330.00, 'cash', 0.00, 12.00, 39.60, 369.60, 'INR', 1),
  (930171, 920057, 'logged_in', 379.50, 'cash', 0.00, 12.00, 45.54, 425.04, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940183, 920057, 'b2b', 1000, 1999, 300.00, 'INR', 1),
  (940184, 920057, 'b2b', 2000, 4999, 285.00, 'INR', 1),
  (940185, 920057, 'b2b', 5000, 9999, 265.00, 'INR', 1),
  (940186, 920057, 'b2b', 10000, NULL, 250.00, 'INR', 1);

-- Product: URINE STRIP 11 PARAMETER (BIU-008)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910058, 9, NULL, 'urine-strip-11-parameter-biu-008', 'BIO-BIU', 1, 'BIO-BIU-008', 'Urine Strip 11 Parameter', 'Biogenix', 'Urine test strip for 11 parameter analysis.', 'Urine Strip 11 Parameter for comprehensive urinalysis.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920058, 910058, 'BIO-BIU-008-100T', '100 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930172, 920058, 'public', 400.00, 'cash', 0.00, 12.00, 48.00, 448.00, 'INR', 1),
  (930173, 920058, 'retail', 400.00, 'cash', 0.00, 12.00, 48.00, 448.00, 'INR', 1),
  (930174, 920058, 'logged_in', 460.00, 'cash', 0.00, 12.00, 55.20, 515.20, 'INR', 1);


-- ============================================================
-- SECTION 5: SEROLOGY
-- ============================================================

-- Product: ASO LATEX (BIS-001)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910059, 8, NULL, 'aso-latex-bis-001', 'BIO-BIS', 1, 'BIO-BIS-001', 'ASO Latex', 'Biogenix', 'Serology latex agglutination kit for ASO detection.', 'ASO Latex kit for serology testing.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920059, 910059, 'BIO-BIS-001-25T', '25 Tests', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930175, 920059, 'public', 275.00, 'cash', 0.00, 12.00, 33.00, 308.00, 'INR', 1),
  (930176, 920059, 'retail', 275.00, 'cash', 0.00, 12.00, 33.00, 308.00, 'INR', 1),
  (930177, 920059, 'logged_in', 316.25, 'cash', 0.00, 12.00, 37.95, 354.20, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940187, 920059, 'b2b', 10, 24, 255.00, 'INR', 1),
  (940188, 920059, 'b2b', 25, 49, 245.00, 'INR', 1),
  (940189, 920059, 'b2b', 50, 99, 230.00, 'INR', 1),
  (940190, 920059, 'b2b', 100, NULL, 215.00, 'INR', 1);

-- Product: CRP LATEX (BIS-002)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910060, 8, NULL, 'crp-latex-bis-002', 'BIO-BIS', 1, 'BIO-BIS-002', 'CRP Latex', 'Biogenix', 'Serology latex agglutination kit for CRP detection.', 'CRP Latex kit for serology testing.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920060, 910060, 'BIO-BIS-002-25T', '25 Tests', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930178, 920060, 'public', 200.00, 'cash', 0.00, 12.00, 24.00, 224.00, 'INR', 1),
  (930179, 920060, 'retail', 200.00, 'cash', 0.00, 12.00, 24.00, 224.00, 'INR', 1),
  (930180, 920060, 'logged_in', 230.00, 'cash', 0.00, 12.00, 27.60, 257.60, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940191, 920060, 'b2b', 10, 24, 182.00, 'INR', 1),
  (940192, 920060, 'b2b', 25, 49, 165.00, 'INR', 1),
  (940193, 920060, 'b2b', 50, 99, 150.00, 'INR', 1),
  (940194, 920060, 'b2b', 100, NULL, 135.00, 'INR', 1);

-- Product: RF LATEX (BIS-003)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910061, 8, NULL, 'rf-latex-bis-003', 'BIO-BIS', 1, 'BIO-BIS-003', 'RF Latex', 'Biogenix', 'Serology latex agglutination kit for rheumatoid factor detection.', 'RF Latex kit for serology testing.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920061, 910061, 'BIO-BIS-003-25T', '25 Tests', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930181, 920061, 'public', 180.00, 'cash', 0.00, 12.00, 21.60, 201.60, 'INR', 1),
  (930182, 920061, 'retail', 180.00, 'cash', 0.00, 12.00, 21.60, 201.60, 'INR', 1),
  (930183, 920061, 'logged_in', 207.00, 'cash', 0.00, 12.00, 24.84, 231.84, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940195, 920061, 'b2b', 10, 24, 165.00, 'INR', 1),
  (940196, 920061, 'b2b', 25, 49, 150.00, 'INR', 1),
  (940197, 920061, 'b2b', 50, 99, 132.00, 'INR', 1),
  (940198, 920061, 'b2b', 100, NULL, 125.00, 'INR', 1);

-- Product: RPR/VDRL LATEX (BIS-004)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910062, 8, NULL, 'rpr-vdrl-latex-bis-004', 'BIO-BIS', 1, 'BIO-BIS-004', 'RPR/VDRL Latex', 'Biogenix', 'Serology latex kit for RPR/VDRL syphilis detection.', 'RPR/VDRL Latex kit for syphilis serology testing.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920062, 910062, 'BIO-BIS-004-50T', '50 Tests', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930184, 920062, 'public', 160.00, 'cash', 0.00, 12.00, 19.20, 179.20, 'INR', 1),
  (930185, 920062, 'retail', 160.00, 'cash', 0.00, 12.00, 19.20, 179.20, 'INR', 1),
  (930186, 920062, 'logged_in', 184.00, 'cash', 0.00, 12.00, 22.08, 206.08, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940199, 920062, 'b2b', 10, 24, 150.00, 'INR', 1),
  (940200, 920062, 'b2b', 25, 49, 135.00, 'INR', 1),
  (940201, 920062, 'b2b', 50, 99, 125.00, 'INR', 1),
  (940202, 920062, 'b2b', 100, NULL, 115.00, 'INR', 1);


-- ============================================================
-- SECTION 6: HEMATOLOGY REAGENTS
-- ============================================================

-- Product: DILUENT (BIH-001)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910063, 4, NULL, 'hematology-diluent-bih-001', 'BIO-BIH', 1, 'BIO-BIH-001', 'Hematology Diluent', 'Biogenix', 'Diluent reagent for hematology analyzers.', 'Hematology Diluent for use with 3-part and 5-part hematology analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920063, 910063, 'BIO-BIH-001-20LTR', '20 Ltr', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930187, 920063, 'public', 1250.00, 'cash', 0.00, 12.00, 150.00, 1400.00, 'INR', 1),
  (930188, 920063, 'retail', 1250.00, 'cash', 0.00, 12.00, 150.00, 1400.00, 'INR', 1),
  (930189, 920063, 'logged_in', 1437.50, 'cash', 0.00, 12.00, 172.50, 1610.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940203, 920063, 'b2b', 10, 24, 1150.00, 'INR', 1),
  (940204, 920063, 'b2b', 25, 49, 1000.00, 'INR', 1),
  (940205, 920063, 'b2b', 50, 99, 925.00, 'INR', 1),
  (940206, 920063, 'b2b', 100, NULL, 850.00, 'INR', 1);

-- Product: LYSE (BIH-002)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910064, 4, NULL, 'hematology-lyse-bih-002', 'BIO-BIH', 1, 'BIO-BIH-002', 'Hematology Lyse', 'Biogenix', 'Lyse reagent for hematology analyzers.', 'Hematology Lyse reagent for use with 3-part and 5-part hematology analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920064, 910064, 'BIO-BIH-002-500ML', '500 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930190, 920064, 'public', 1250.00, 'cash', 0.00, 12.00, 150.00, 1400.00, 'INR', 1),
  (930191, 920064, 'retail', 1250.00, 'cash', 0.00, 12.00, 150.00, 1400.00, 'INR', 1),
  (930192, 920064, 'logged_in', 1437.50, 'cash', 0.00, 12.00, 172.50, 1610.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940207, 920064, 'b2b', 10, 24, 1150.00, 'INR', 1),
  (940208, 920064, 'b2b', 25, 49, 1000.00, 'INR', 1),
  (940209, 920064, 'b2b', 50, 99, 925.00, 'INR', 1),
  (940210, 920064, 'b2b', 100, NULL, 850.00, 'INR', 1);

-- Product: RINSE (BIH-003)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910065, 4, NULL, 'hematology-rinse-bih-003', 'BIO-BIH', 1, 'BIO-BIH-003', 'Hematology Rinse', 'Biogenix', 'Rinse reagent for hematology analyzers.', 'Hematology Rinse reagent for use with hematology analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920065, 910065, 'BIO-BIH-003-20LTR', '20 Ltr', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930193, 920065, 'public', 1250.00, 'cash', 0.00, 12.00, 150.00, 1400.00, 'INR', 1),
  (930194, 920065, 'retail', 1250.00, 'cash', 0.00, 12.00, 150.00, 1400.00, 'INR', 1),
  (930195, 920065, 'logged_in', 1437.50, 'cash', 0.00, 12.00, 172.50, 1610.00, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940211, 920065, 'b2b', 10, 24, 1150.00, 'INR', 1),
  (940212, 920065, 'b2b', 25, 49, 1000.00, 'INR', 1),
  (940213, 920065, 'b2b', 50, 99, 925.00, 'INR', 1),
  (940214, 920065, 'b2b', 100, NULL, 850.00, 'INR', 1);

-- Product: PROBE CLEANER (BIH-004)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910066, 4, NULL, 'hematology-probe-cleaner-bih-004', 'BIO-BIH', 1, 'BIO-BIH-004', 'Probe Cleaner', 'Biogenix', 'Probe cleaner reagent for hematology analyzers.', 'Probe Cleaner for use with hematology analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920066, 910066, 'BIO-BIH-004-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930196, 920066, 'public', 350.00, 'cash', 0.00, 12.00, 42.00, 392.00, 'INR', 1),
  (930197, 920066, 'retail', 350.00, 'cash', 0.00, 12.00, 42.00, 392.00, 'INR', 1),
  (930198, 920066, 'logged_in', 402.50, 'cash', 0.00, 12.00, 48.30, 450.80, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940215, 920066, 'b2b', 10, 24, 300.00, 'INR', 1),
  (940216, 920066, 'b2b', 25, 49, 250.00, 'INR', 1),
  (940217, 920066, 'b2b', 50, 99, 225.00, 'INR', 1),
  (940218, 920066, 'b2b', 100, NULL, 200.00, 'INR', 1);

-- Product: EZ CLEANER (BIH-005)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910067, 4, NULL, 'hematology-ez-cleaner-bih-005', 'BIO-BIH', 1, 'BIO-BIH-005', 'EZ Cleaner', 'Biogenix', 'EZ cleaner reagent for hematology analyzers.', 'EZ Cleaner for use with hematology analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920067, 910067, 'BIO-BIH-005-100ML', '100 ml', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930199, 920067, 'public', 350.00, 'cash', 0.00, 12.00, 42.00, 392.00, 'INR', 1),
  (930200, 920067, 'retail', 350.00, 'cash', 0.00, 12.00, 42.00, 392.00, 'INR', 1),
  (930201, 920067, 'logged_in', 402.50, 'cash', 0.00, 12.00, 48.30, 450.80, 'INR', 1);

INSERT INTO product_bulk_prices (id, product_variant_id, applies_to_user_type, min_quantity, max_quantity, amount, currency, is_active)
VALUES
  (940219, 920067, 'b2b', 10, 24, 300.00, 'INR', 1),
  (940220, 920067, 'b2b', 25, 49, 250.00, 'INR', 1),
  (940221, 920067, 'b2b', 50, 99, 225.00, 'INR', 1),
  (940222, 920067, 'b2b', 100, NULL, 200.00, 'INR', 1);


-- ============================================================
-- SECTION 7: MICROBIOLOGY - COVID-19 RT PCR KIT
-- ============================================================

-- Product: COVID-19 RT PCR KIT (BIM-002)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910068, 13, NULL, 'covid-19-rt-pcr-kit-bim-002', 'BIO-BIM', 1, 'BIO-BIM-002', 'COVID-19 RT PCR Kit', 'Biogenix', 'RT PCR kit for COVID-19 detection.', 'COVID-19 RT PCR Kit for molecular diagnosis of SARS-CoV-2.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920068, 910068, 'BIO-BIM-002-100T', '100 Tests', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930202, 920068, 'public', 35000.00, 'cash', 0.00, 12.00, 4200.00, 39200.00, 'INR', 1),
  (930203, 920068, 'retail', 6500.00, 'cash', 0.00, 12.00, 780.00, 7280.00, 'INR', 1),
  (930204, 920068, 'logged_in', 7475.00, 'cash', 0.00, 12.00, 897.00, 8372.00, 'INR', 1);


-- ============================================================
-- SECTION 8: CLIA ANALYZER REAGENTS (SELECTED KEY MARKERS)
-- ============================================================

-- Product: TSH CLIA Reagent
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910069, 11, NULL, 'tsh-clia-reagent', 'BIO-CLIA', 1, 'BIO-CLIA-TSH', 'TSH CLIA Reagent', 'Biogenix', 'CLIA analyzer reagent for TSH measurement.', 'TSH CLIA Reagent for fully automated chemiluminescence immunoassay analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920069, 910069, 'BIO-CLIA-TSH-100T', '100 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930205, 920069, 'public', 1375.00, 'cash', 0.00, 12.00, 165.00, 1540.00, 'INR', 1),
  (930206, 920069, 'retail', 1375.00, 'cash', 0.00, 12.00, 165.00, 1540.00, 'INR', 1),
  (930207, 920069, 'logged_in', 1581.25, 'cash', 0.00, 12.00, 189.75, 1771.00, 'INR', 1);

-- Product: Vitamin D CLIA Reagent
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910070, 11, NULL, 'vitamin-d-clia-reagent', 'BIO-CLIA', 1, 'BIO-CLIA-VITD', 'Vitamin D CLIA Reagent', 'Biogenix', 'CLIA analyzer reagent for Vitamin D measurement.', 'Vitamin D CLIA Reagent for fully automated chemiluminescence immunoassay analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920070, 910070, 'BIO-CLIA-VITD-100T', '100 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930208, 920070, 'public', 8500.00, 'cash', 0.00, 12.00, 1020.00, 9520.00, 'INR', 1),
  (930209, 920070, 'retail', 8500.00, 'cash', 0.00, 12.00, 1020.00, 9520.00, 'INR', 1),
  (930210, 920070, 'logged_in', 9775.00, 'cash', 0.00, 12.00, 1173.00, 10948.00, 'INR', 1);

-- Product: AFP CLIA Reagent (Tumor Marker)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910071, 11, NULL, 'afp-clia-reagent', 'BIO-CLIA', 1, 'BIO-CLIA-AFP', 'AFP CLIA Reagent', 'Biogenix', 'CLIA analyzer reagent for AFP tumor marker measurement.', 'AFP CLIA Reagent for fully automated chemiluminescence immunoassay analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920071, 910071, 'BIO-CLIA-AFP-100T', '100 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930211, 920071, 'public', 4000.00, 'cash', 0.00, 12.00, 480.00, 4480.00, 'INR', 1),
  (930212, 920071, 'retail', 4000.00, 'cash', 0.00, 12.00, 480.00, 4480.00, 'INR', 1),
  (930213, 920071, 'logged_in', 4600.00, 'cash', 0.00, 12.00, 552.00, 5152.00, 'INR', 1);

-- Product: CEA CLIA Reagent (Tumor Marker)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910072, 11, NULL, 'cea-clia-reagent', 'BIO-CLIA', 1, 'BIO-CLIA-CEA', 'CEA CLIA Reagent', 'Biogenix', 'CLIA analyzer reagent for CEA tumor marker measurement.', 'CEA CLIA Reagent for fully automated chemiluminescence immunoassay analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920072, 910072, 'BIO-CLIA-CEA-100T', '100 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930214, 920072, 'public', 4000.00, 'cash', 0.00, 12.00, 480.00, 4480.00, 'INR', 1),
  (930215, 920072, 'retail', 4000.00, 'cash', 0.00, 12.00, 480.00, 4480.00, 'INR', 1),
  (930216, 920072, 'logged_in', 4600.00, 'cash', 0.00, 12.00, 552.00, 5152.00, 'INR', 1);

-- Product: HBsAg CLIA Reagent
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910073, 11, NULL, 'hbsag-clia-reagent', 'BIO-CLIA', 1, 'BIO-CLIA-HBSAG', 'HBsAg CLIA Reagent', 'Biogenix', 'CLIA analyzer reagent for HBsAg detection.', 'HBsAg CLIA Reagent for fully automated chemiluminescence immunoassay analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920073, 910073, 'BIO-CLIA-HBSAG-100T', '100 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930217, 920073, 'public', 7500.00, 'cash', 0.00, 12.00, 900.00, 8400.00, 'INR', 1),
  (930218, 920073, 'retail', 7500.00, 'cash', 0.00, 12.00, 900.00, 8400.00, 'INR', 1),
  (930219, 920073, 'logged_in', 8625.00, 'cash', 0.00, 12.00, 1035.00, 9660.00, 'INR', 1);

-- Product: PCT CLIA Reagent (Inflammation)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910074, 11, NULL, 'pct-clia-reagent', 'BIO-CLIA', 1, 'BIO-CLIA-PCT', 'PCT CLIA Reagent', 'Biogenix', 'CLIA analyzer reagent for Procalcitonin measurement.', 'PCT CLIA Reagent for fully automated chemiluminescence immunoassay analyzers.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920074, 910074, 'BIO-CLIA-PCT-100T', '100 Test', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930220, 920074, 'public', 17500.00, 'cash', 0.00, 12.00, 2100.00, 19600.00, 'INR', 1),
  (930221, 920074, 'retail', 17500.00, 'cash', 0.00, 12.00, 2100.00, 19600.00, 'INR', 1),
  (930222, 920074, 'logged_in', 20125.00, 'cash', 0.00, 12.00, 2415.00, 22540.00, 'INR', 1);


-- ============================================================
-- SECTION 9: IVD INSTRUMENTS
-- ============================================================

-- Product: H-30 Pro 3-Part Hematology Analyzer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910075, 15, NULL, 'h-30-pro-3-part-hematology-analyzer', 'BIO-INST', 1, 'BIO-INST-H30PRO', 'H-30 Pro 3-Part Hematology Analyzer', 'Biogenix', '3-Part Hematology Analyzer for routine CBC testing.', 'The H-30 Pro is a 3-part hematology analyzer designed for routine CBC processing in laboratories and clinics.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920075, 910075, 'BIO-INST-H30PRO-BASE', 'Base', 1, 5, 1, 5, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930223, 920075, 'public', 55000.00, 'cash', 0.00, 18.00, 9900.00, 64900.00, 'INR', 1),
  (930224, 920075, 'retail', 55000.00, 'cash', 0.00, 18.00, 9900.00, 64900.00, 'INR', 1),
  (930225, 920075, 'logged_in', 63250.00, 'cash', 0.00, 18.00, 11385.00, 74635.00, 'INR', 1);

-- Product: H-60 5-Part Hematology Analyzer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910076, 15, NULL, 'h-60-5-part-hematology-analyzer', 'BIO-INST', 1, 'BIO-INST-H60', 'H-60 5-Part Hematology Analyzer', 'Biogenix', '5-Part Hematology Analyzer for comprehensive CBC testing.', 'The H-60 is a 5-part hematology analyzer designed for comprehensive CBC processing in mid-to-high volume laboratories.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920076, 910076, 'BIO-INST-H60-BASE', 'Base', 1, 5, 1, 5, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930226, 920076, 'public', 375000.00, 'cash', 0.00, 18.00, 67500.00, 442500.00, 'INR', 1),
  (930227, 920076, 'retail', 375000.00, 'cash', 0.00, 18.00, 67500.00, 442500.00, 'INR', 1),
  (930228, 920076, 'logged_in', 431250.00, 'cash', 0.00, 18.00, 77625.00, 508875.00, 'INR', 1);

-- Product: H-60S 5-Part Hematology Analyzer with Autoloader
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910077, 15, NULL, 'h-60s-5-part-hematology-analyzer-autoloader', 'BIO-INST', 1, 'BIO-INST-H60S', 'H-60S 5-Part Hematology Analyzer with Autoloader', 'Biogenix', '5-Part Hematology Analyzer with Autoloader for high-throughput labs.', 'The H-60S is a 5-part hematology analyzer with autoloader designed for high-throughput laboratory environments.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920077, 910077, 'BIO-INST-H60S-BASE', 'Base', 1, 3, 1, 3, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930229, 920077, 'public', 550000.00, 'cash', 0.00, 18.00, 99000.00, 649000.00, 'INR', 1),
  (930230, 920077, 'retail', 550000.00, 'cash', 0.00, 18.00, 99000.00, 649000.00, 'INR', 1),
  (930231, 920077, 'logged_in', 632500.00, 'cash', 0.00, 18.00, 113850.00, 746350.00, 'INR', 1);

-- Product: BI-10S Semi-Auto Biochemistry Analyzer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910078, 15, NULL, 'bi-10s-semi-auto-biochemistry-analyzer', 'BIO-INST', 1, 'BIO-INST-BI10S', 'BI-10S Semi-Auto Biochemistry Analyzer', 'Biogenix', 'Semi-automated biochemistry analyzer with incubator.', 'The BI-10S is a semi-auto biochemistry analyzer with incubator, suitable for small to mid-sized labs.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920078, 910078, 'BIO-INST-BI10S-BASE', 'Base', 1, 5, 1, 5, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930232, 920078, 'public', 45000.00, 'cash', 0.00, 18.00, 8100.00, 53100.00, 'INR', 1),
  (930233, 920078, 'retail', 45000.00, 'cash', 0.00, 18.00, 8100.00, 53100.00, 'INR', 1),
  (930234, 920078, 'logged_in', 51750.00, 'cash', 0.00, 18.00, 9315.00, 61065.00, 'INR', 1);

-- Product: BI-280 Fully-Auto Biochemistry Analyzer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910079, 15, NULL, 'bi-280-fully-auto-biochemistry-analyzer', 'BIO-INST', 1, 'BIO-INST-BI280', 'BI-280 Fully-Auto Biochemistry Analyzer', 'Biogenix', 'Fully automated biochemistry analyzer for high-throughput labs.', 'The BI-280 is a fully automated biochemistry analyzer designed for busy diagnostic labs needing high throughput.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920079, 910079, 'BIO-INST-BI280-BASE', 'Base', 1, 3, 1, 3, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930235, 920079, 'public', 170000.00, 'cash', 0.00, 18.00, 30600.00, 200600.00, 'INR', 1),
  (930236, 920079, 'retail', 170000.00, 'cash', 0.00, 18.00, 30600.00, 200600.00, 'INR', 1),
  (930237, 920079, 'logged_in', 195500.00, 'cash', 0.00, 18.00, 35190.00, 230690.00, 'INR', 1);

-- Product: BI-1000 Fully Automated CLIA Analyzer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910080, 15, NULL, 'bi-1000-fully-automated-clia-analyzer', 'BIO-INST', 1, 'BIO-INST-BI1000', 'BI-1000 Fully Automated CLIA Analyzer', 'Biogenix', 'Fully automated chemiluminescence immunoassay analyzer.', 'The BI-1000 is a fully automated CLIA analyzer for comprehensive immunoassay testing.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920080, 910080, 'BIO-INST-BI1000-BASE', 'Base', 1, 3, 1, 3, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930238, 920080, 'public', 395000.00, 'cash', 0.00, 18.00, 71100.00, 466100.00, 'INR', 1),
  (930239, 920080, 'retail', 395000.00, 'cash', 0.00, 18.00, 71100.00, 466100.00, 'INR', 1),
  (930240, 920080, 'logged_in', 454250.00, 'cash', 0.00, 18.00, 81765.00, 536015.00, 'INR', 1);

-- Product: BI-2000 Fully Automated CLIA Analyzer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910081, 15, NULL, 'bi-2000-fully-automated-clia-analyzer', 'BIO-INST', 1, 'BIO-INST-BI2000', 'BI-2000 Fully Automated CLIA Analyzer', 'Biogenix', 'High throughput fully automated chemiluminescence immunoassay analyzer.', 'The BI-2000 is a high-throughput fully automated CLIA analyzer for comprehensive immunoassay testing.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920081, 910081, 'BIO-INST-BI2000-BASE', 'Base', 1, 3, 1, 3, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930241, 920081, 'public', 570000.00, 'cash', 0.00, 18.00, 102600.00, 672600.00, 'INR', 1),
  (930242, 920081, 'retail', 570000.00, 'cash', 0.00, 18.00, 102600.00, 672600.00, 'INR', 1),
  (930243, 920081, 'logged_in', 655500.00, 'cash', 0.00, 18.00, 117990.00, 773490.00, 'INR', 1);

-- Product: BI-100CL Single Channel Coagulation Analyzer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910082, 15, NULL, 'bi-100cl-single-channel-coagulation-analyzer', 'BIO-INST', 1, 'BIO-INST-BI100CL', 'BI-100CL Single Channel Coagulation Analyzer', 'Biogenix', 'Single channel coagulation analyzer for routine coagulation testing.', 'The BI-100CL is a single channel coagulation analyzer suitable for small labs and clinics.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920082, 910082, 'BIO-INST-BI100CL-BASE', 'Base', 1, 5, 1, 5, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930244, 920082, 'public', 85000.00, 'cash', 0.00, 18.00, 15300.00, 100300.00, 'INR', 1),
  (930245, 920082, 'retail', 85000.00, 'cash', 0.00, 18.00, 15300.00, 100300.00, 'INR', 1),
  (930246, 920082, 'logged_in', 97750.00, 'cash', 0.00, 18.00, 17595.00, 115345.00, 'INR', 1);

-- Product: BI-200CL Double Channel Coagulation Analyzer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910083, 15, NULL, 'bi-200cl-double-channel-coagulation-analyzer', 'BIO-INST', 1, 'BIO-INST-BI200CL', 'BI-200CL Double Channel Coagulation Analyzer', 'Biogenix', 'Double channel coagulation analyzer for routine coagulation testing.', 'The BI-200CL is a double channel coagulation analyzer for mid-volume coagulation testing.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920083, 910083, 'BIO-INST-BI200CL-BASE', 'Base', 1, 5, 1, 5, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930247, 920083, 'public', 95000.00, 'cash', 0.00, 18.00, 17100.00, 112100.00, 'INR', 1),
  (930248, 920083, 'retail', 95000.00, 'cash', 0.00, 18.00, 17100.00, 112100.00, 'INR', 1),
  (930249, 920083, 'logged_in', 109250.00, 'cash', 0.00, 18.00, 19665.00, 128915.00, 'INR', 1);

-- Product: BI-400CL Four Channel Coagulation Analyzer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910084, 15, NULL, 'bi-400cl-four-channel-coagulation-analyzer', 'BIO-INST', 1, 'BIO-INST-BI400CL', 'BI-400CL Four Channel Coagulation Analyzer', 'Biogenix', 'Four channel coagulation analyzer for high-volume coagulation testing.', 'The BI-400CL is a four channel coagulation analyzer suited for high-volume coagulation testing labs.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920084, 910084, 'BIO-INST-BI400CL-BASE', 'Base', 1, 3, 1, 3, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930250, 920084, 'public', 95000.00, 'cash', 0.00, 18.00, 17100.00, 112100.00, 'INR', 1),
  (930251, 920084, 'retail', 95000.00, 'cash', 0.00, 18.00, 17100.00, 112100.00, 'INR', 1),
  (930252, 920084, 'logged_in', 109250.00, 'cash', 0.00, 18.00, 19665.00, 128915.00, 'INR', 1);

-- Product: BI-400 Urine Analyzer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910085, 15, NULL, 'bi-400-urine-analyzer', 'BIO-INST', 1, 'BIO-INST-BI400', 'BI-400 Urine Analyzer', 'Biogenix', 'Automated urine analyzer for routine urinalysis.', 'The BI-400 Urine Analyzer delivers fast and reliable urinalysis results for clinical labs.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920085, 910085, 'BIO-INST-BI400-BASE', 'Base', 1, 5, 1, 5, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930253, 920085, 'public', 25000.00, 'cash', 0.00, 18.00, 4500.00, 29500.00, 'INR', 1),
  (930254, 920085, 'retail', 25000.00, 'cash', 0.00, 18.00, 4500.00, 29500.00, 'INR', 1),
  (930255, 920085, 'logged_in', 28750.00, 'cash', 0.00, 18.00, 5175.00, 33925.00, 'INR', 1);

-- Product: BI-180 POCT Analyzer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910086, 15, NULL, 'bi-180-poct-analyzer', 'BIO-INST', 1, 'BIO-INST-BI180', 'BI-180 POCT Analyzer', 'Biogenix', 'Point-of-care testing analyzer for rapid diagnostics.', 'The BI-180 POCT Analyzer delivers rapid immunoassay results at the point of care.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920086, 910086, 'BIO-INST-BI180-BASE', 'Base', 1, 5, 1, 5, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930256, 920086, 'public', 60000.00, 'cash', 0.00, 18.00, 10800.00, 70800.00, 'INR', 1),
  (930257, 920086, 'retail', 60000.00, 'cash', 0.00, 18.00, 10800.00, 70800.00, 'INR', 1),
  (930258, 920086, 'logged_in', 69000.00, 'cash', 0.00, 18.00, 12420.00, 81420.00, 'INR', 1);

-- Product: BI-32 Auto Blood Culture System
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910087, 15, NULL, 'bi-32-auto-blood-culture-system', 'BIO-INST', 1, 'BIO-INST-BI32', 'BI-32 Auto Blood Culture System', 'Biogenix', 'Automated blood culture system for microbiology labs.', 'The BI-32 Auto Blood Culture System provides reliable automated blood culture detection for microbiology departments.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920087, 910087, 'BIO-INST-BI32-BASE', 'Base', 1, 3, 1, 3, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930259, 920087, 'public', 75000.00, 'cash', 0.00, 18.00, 13500.00, 88500.00, 'INR', 1),
  (930260, 920087, 'retail', 75000.00, 'cash', 0.00, 18.00, 13500.00, 88500.00, 'INR', 1),
  (930261, 920087, 'logged_in', 86250.00, 'cash', 0.00, 18.00, 15525.00, 101775.00, 'INR', 1);

-- Product: BI-3000PT HbA1C/HPLC Analyzer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910088, 15, NULL, 'bi-3000pt-hba1c-hplc-analyzer', 'BIO-INST', 1, 'BIO-INST-BI3000PT', 'BI-3000PT HbA1C/HPLC Analyzer', 'Biogenix', 'Glycated hemoglobin HbA1C/HPLC analyzer for diabetes monitoring.', 'The BI-3000PT is a dedicated HbA1C/HPLC analyzer for precise glycated hemoglobin measurement.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920088, 910088, 'BIO-INST-BI3000PT-BASE', 'Base', 1, 3, 1, 3, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930262, 920088, 'public', 120000.00, 'cash', 0.00, 18.00, 21600.00, 141600.00, 'INR', 1),
  (930263, 920088, 'retail', 120000.00, 'cash', 0.00, 18.00, 21600.00, 141600.00, 'INR', 1),
  (930264, 920088, 'logged_in', 138000.00, 'cash', 0.00, 18.00, 24840.00, 162840.00, 'INR', 1);

-- Product: BI-LYTE Electrolyte Analyzer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910089, 15, NULL, 'bi-lyte-electrolyte-analyzer', 'BIO-INST', 1, 'BIO-INST-BILYTE', 'BI-LYTE Electrolyte Analyzer', 'Biogenix', 'Electrolyte analyzer for Na, K, Cl, and other ion measurement.', 'The BI-LYTE Electrolyte Analyzer provides rapid and accurate electrolyte measurement for clinical labs.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920089, 910089, 'BIO-INST-BILYTE-BASE', 'Base', 1, 5, 1, 5, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930265, 920089, 'public', 245000.00, 'cash', 0.00, 18.00, 44100.00, 289100.00, 'INR', 1),
  (930266, 920089, 'retail', 245000.00, 'cash', 0.00, 18.00, 44100.00, 289100.00, 'INR', 1),
  (930267, 920089, 'logged_in', 281750.00, 'cash', 0.00, 18.00, 50715.00, 332465.00, 'INR', 1);

-- Product: i15 Blood Gas & Chemistry Analyzer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910090, 15, NULL, 'i15-blood-gas-chemistry-analyzer', 'BIO-INST', 1, 'BIO-INST-I15', 'i15 Blood Gas & Chemistry Analyzer', 'Biogenix', 'Blood gas and chemistry analyzer for critical care testing.', 'The i15 Blood Gas & Chemistry Analyzer delivers rapid critical care results at the bedside or in the lab.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920090, 910090, 'BIO-INST-I15-BASE', 'Base', 1, 3, 1, 3, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930268, 920090, 'public', 550000.00, 'cash', 0.00, 18.00, 99000.00, 649000.00, 'INR', 1),
  (930269, 920090, 'retail', 550000.00, 'cash', 0.00, 18.00, 99000.00, 649000.00, 'INR', 1),
  (930270, 920090, 'logged_in', 632500.00, 'cash', 0.00, 18.00, 113850.00, 746350.00, 'INR', 1);

-- Product: BIR-21 Microplate Reader
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910091, 15, NULL, 'bir-21-microplate-reader', 'BIO-INST', 1, 'BIO-INST-BIR21', 'BIR-21 Microplate Reader', 'Biogenix', 'Microplate reader for ELISA and photometric assays.', 'The BIR-21 Microplate Reader is designed for ELISA-based assay reading and photometric measurements.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920091, 910091, 'BIO-INST-BIR21-BASE', 'Base', 1, 5, 1, 5, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930271, 920091, 'public', 935000.00, 'cash', 0.00, 18.00, 168300.00, 1103300.00, 'INR', 1),
  (930272, 920091, 'retail', 935000.00, 'cash', 0.00, 18.00, 168300.00, 1103300.00, 'INR', 1),
  (930273, 920091, 'logged_in', 1075250.00, 'cash', 0.00, 18.00, 193545.00, 1268795.00, 'INR', 1);

-- Product: BIW-31 Microplate Washer
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910092, 15, NULL, 'biw-31-microplate-washer', 'BIO-INST', 1, 'BIO-INST-BIW31', 'BIW-31 Microplate Washer', 'Biogenix', 'Automated microplate washer for ELISA assays.', 'The BIW-31 Microplate Washer automates the wash steps in ELISA and other microplate-based assays.', 18.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920092, 910092, 'BIO-INST-BIW31-BASE', 'Base', 1, 5, 1, 5, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930274, 920092, 'public', 1650000.00, 'cash', 0.00, 18.00, 297000.00, 1947000.00, 'INR', 1),
  (930275, 920092, 'retail', 1650000.00, 'cash', 0.00, 18.00, 297000.00, 1947000.00, 'INR', 1),
  (930276, 920092, 'logged_in', 1897500.00, 'cash', 0.00, 18.00, 341550.00, 2239050.00, 'INR', 1);


-- ============================================================
-- SECTION 10: BLOOD GAS & CHEMISTRY ANALYZER CONSUMABLES
-- ============================================================

-- Product: 10 Parameter Test Cartridge (i15)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910093, 15, NULL, 'i15-10-parameter-test-cartridge', 'BIO-BGC', 1, 'BIO-BGC-G10', 'i15 10 Parameter Test Cartridge', 'Biogenix', 'Test cartridge for i15 blood gas & chemistry analyzer, 10 parameters.', '10 Parameter Test Cartridge for use with the i15 Blood Gas & Chemistry Analyzer.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920093, 910093, 'BIO-BGC-G10-25CART', '25 Cartridges', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930277, 920093, 'public', 1110.00, 'cash', 0.00, 12.00, 133.20, 1243.20, 'INR', 1),
  (930278, 920093, 'retail', 375.00, 'cash', 0.00, 12.00, 45.00, 420.00, 'INR', 1),
  (930279, 920093, 'logged_in', 431.25, 'cash', 0.00, 12.00, 51.75, 483.00, 'INR', 1);

-- Product: HbA1c Assay Kit HPLC (BI-3000PT)
INSERT INTO products (id, category_id, subcategory_id, slug, base_sku, is_published, sku, name, brand, description, product_overview, gst_rate, visibility_scope, is_active)
VALUES (910094, 15, NULL, 'hba1c-assay-kit-hplc', 'BIO-HBA', 1, 'BIO-HBA-CP100', 'HbA1c Assay Kit (HPLC)', 'Biogenix', 'HbA1c assay kit for use with BI-3000PT HPLC analyzer.', 'HbA1c Assay Kit for the BI-3000PT Glycated Hemoglobin Analyzer.', 12.00, 'b2b', 1);

INSERT INTO product_variants (id, product_id, sku, variant_name, min_order_quantity, max_order_quantity, b2b_min_order_quantity, b2b_max_order_quantity, lot_size, stock_quantity, is_active)
VALUES (920094, 910094, 'BIO-HBA-CP100-100T', '100 Tests', 1, NULL, 1, NULL, 1, 0, 1);

INSERT INTO product_prices (id, product_variant_id, price_type, amount, DiscountType, Discount, gst_rate, tax_amount, price_after_gst, currency, is_active)
VALUES
  (930280, 920094, 'public', 9550.00, 'cash', 0.00, 12.00, 1146.00, 10696.00, 'INR', 1),
  (930281, 920094, 'retail', 2800.00, 'cash', 0.00, 12.00, 336.00, 3136.00, 'INR', 1),
  (930282, 920094, 'logged_in', 3220.00, 'cash', 0.00, 12.00, 386.40, 3606.40, 'INR', 1);

COMMIT;
