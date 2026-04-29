@extends('layouts.app')

@section('title', $solutionName)

@section('content')
<div class="min-h-screen bg-slate-50 py-12 md:py-16">
    <section class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-10">
            <p class="text-sm font-medium text-primary-700">Solutions</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">{{ $solutionName }}</h1>

            <div class="mt-8 text-slate-600 prose prose-slate max-w-none">
                @if($solutionSlug === 'oem-third-party-manufacturing')
                    <p>
                        Biogenix offers comprehensive OEM and third-party manufacturing solutions designed to support diagnostic companies, distributors, and healthcare brands in building and scaling their product portfolios with confidence.
                    </p>
                    <p>
                        With a strong foundation in in-house manufacturing and quality-controlled production processes, we enable our partners to outsource the development and manufacturing of diagnostic kits and related products while maintaining strict compliance with industry standards.
                    </p>
                    <p>
                        Our capabilities span across rapid diagnostic tests, clinical chemistry reagents, and serology-based products, ensuring flexibility across multiple diagnostic segments. We work closely with our partners at every stage—from product conceptualization and formulation to packaging, branding, and regulatory support.
                    </p>
                    <p>
                        This collaborative approach allows clients to bring market-ready products under their own brand identity without the need for heavy capital investment in manufacturing infrastructure. Our manufacturing processes are designed for consistency, scalability, and efficiency, ensuring high-quality output across both small and large production volumes.
                    </p>
                    <p>
                        With a focus on reliability, timely delivery, and confidentiality, Biogenix serves as a trusted manufacturing partner for organizations looking to expand their offerings or enter new markets. Whether you are an emerging brand or an established player seeking to optimize costs and improve turnaround time, our OEM solutions provide a seamless pathway to growth, backed by technical expertise and operational excellence.
                    </p>
                    
                    <div class="mt-10">
                        <a href="{{ route('book-meeting') }}" class="inline-flex h-12 items-center justify-center rounded-2xl bg-primary-600 px-6 text-sm font-semibold text-white no-underline transition hover:bg-primary-700">
                            Book a Meeting
                        </a>
                    </div>

                @elseif($solutionSlug === 'integrated-laboratory-solutions')
                    <p>
                        Biogenix provides end-to-end integrated laboratory solutions designed to support healthcare providers, diagnostic centres, and institutions in establishing fully functional, efficient, and scalable diagnostic laboratories.
                    </p>
                    <p>
                        Our approach goes beyond supplying individual instruments—we deliver complete laboratory ecosystems tailored to the specific requirements of each client. From initial planning and infrastructure assessment to equipment selection, installation, and workflow optimization, we ensure that every component of the laboratory is aligned for accuracy, efficiency, and long-term performance.
                    </p>
                    <p>
                        We offer a comprehensive portfolio of diagnostic instruments, including biochemistry analyzers, haematology systems, immunoassay platforms (CLIA), ELISA systems, and supporting equipment, along with a full range of reagents and consumables. Each solution is carefully configured to match the expected testing volume, operational needs, and budget considerations of the client.
                    </p>

                @elseif($solutionSlug === 'emergency-critical-care-diagnostics')
                    <p>
                        Biogenix offers advanced emergency and critical care diagnostic solutions designed to support rapid clinical decision-making in high-pressure environments such as emergency rooms, intensive care units (ICU), and critical care units (CCU). In time-sensitive situations, access to accurate and immediate diagnostic data is essential.
                    </p>
                    <p>
                        Our solutions are built to deliver fast turnaround times, minimal sample processing, and high reliability, enabling clinicians to assess patient conditions and initiate treatment without delay. Our solutions are engineered to integrate seamlessly into emergency workflows, minimizing manual intervention while maximizing efficiency and diagnostic accuracy.
                    </p>
                    <p>
                        With robust technical support, training, and maintenance services, we ensure uninterrupted performance when it matters most. Biogenix empowers healthcare providers with dependable, high-speed diagnostic tools that enhance patient outcomes and support critical care excellence.
                    </p>

                    <h3 class="text-xl font-bold mt-8 mb-4 text-slate-900">1. Core Critical Care Instruments (Primary Emergency Use)</h3>
                    <p>These are your frontline, real emergency devices. They are the most important machines for ICU / ER decision-making.</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">i15 Blood Gas Analyzer</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-180 Fluorescent Immunoassay (POCT Analyzer)</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-LYTE Electrolyte Analyzer</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-ELA Electrolyte Analyzer</a></li>
                    </ul>

                    <h3 class="text-xl font-bold mt-8 mb-4 text-slate-900">2. Point-of-Care & Rapid Testing Devices</h3>
                    <p>Used for instant bedside or emergency screening. These are crucial in ambulances, small ER setups, and rural emergency care.</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Haemoglobin Meter (POCT)</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Creatinine Meter (POCT)</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Lipid Testing System (POCT)</a></li>
                    </ul>

                    <h3 class="text-xl font-bold mt-8 mb-4 text-slate-900">3. Supporting Emergency Lab Instruments</h3>
                    <p>These are not pure "instant" devices but are used in critical workflows within hospitals:</p>
                    
                    <h4 class="font-semibold mt-4 text-slate-800">Hematology (for emergency blood profiling)</h4>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Trinity 3 – 3-Part Hematology Analyzer</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Trinity 5 – 5-Part Hematology Analyzer</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Trinity 5 with Autoloader</a></li>
                    </ul>

                    <h4 class="font-semibold mt-4 text-slate-800">Coagulation (critical in trauma, surgery, ICU)</h4>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-100CL Coagulation Analyzer</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-200CL Coagulation Analyzer</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-400CL Coagulation Analyzer</a></li>
                    </ul>

                    <h4 class="font-semibold mt-4 text-slate-800">Urine (supportive diagnostics in ICU)</h4>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-400 Urine Analyzer</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-U600 Fully Auto Urine Analyzer</a></li>
                    </ul>

                    <h3 class="text-xl font-bold mt-8 mb-4 text-slate-900">4. Emergency-Supporting Advanced Systems</h3>
                    <p>These are used in critical hospital backend support, not immediate bedside—but still part of emergency ecosystem:</p>
                    
                    <h4 class="font-semibold mt-4 text-slate-800">Infection / Sepsis Detection</h4>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BUGZ 32 Fully Automated Blood Culture System</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Blood Culture System (40/60 bottle variants)</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BUGZ160 Blood Culture System with ID/AST</a></li>
                    </ul>

                    <h4 class="font-semibold mt-4 text-slate-800">Microbiology (infection identification)</h4>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-120MA Microbial ID/AST System</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-96A Fully Automated Microbial ID/AST System</a></li>
                    </ul>

                    <h4 class="font-semibold mt-4 text-slate-800">Immunoassay (for cardiac markers, hormones in emergency cases)</h4>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-1800 CLIA Analyzer</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Morpheus CLIA Analyzer</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-2000 CLIA Analyzer</a></li>
                    </ul>

                    <h3 class="text-xl font-bold mt-8 mb-4 text-slate-900">5. Rapid Diagnostic Kits (VERY IMPORTANT)</h3>
                    <p>Massively relevant in emergencies.</p>
                    
                    <h4 class="font-semibold mt-4 text-slate-800">Critical Emergency Markers</h4>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Troponin I</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">CK-MB</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">D-Dimer</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">CRP</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Procalcitonin (PCT)</a></li>
                    </ul>

                    <h4 class="font-semibold mt-4 text-slate-800">Infectious Emergency Detection</h4>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Dengue NS1 / IgG / IgM</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Malaria</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">HIV</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">HBsAg</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Typhoid</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Influenza</a></li>
                    </ul>

                @elseif($solutionSlug === 'preventive-screening-solutions')
                    <p>
                        Early detection, mass testing, population-level diagnostics, and routine health checkups are vital components of modern healthcare.
                    </p>

                    <h3 class="text-xl font-bold mt-8 mb-4 text-slate-900">1. ELISA-Based Screening Panels (High-Volume Lab Screening)</h3>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Autoimmune Screening</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Cancer Screening</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Cardiac Risk Screening</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Allergy Screening</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Bone & Metabolic Screening</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Fertility & Hormonal Screening</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Steroid & Hormone Panel</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Thyroid Screening</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Infectious Disease Screening</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Parasitology Screening</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Diabetes Screening</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Anemia Screening</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Specialty Preventive Panels</a></li>
                    </ul>

                    <h3 class="text-xl font-bold mt-8 mb-4 text-slate-900">2. Rapid Test Kits (Mass Screening / Field Use)</h3>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Infectious Screening</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Preventive Biomarkers</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Other Screening</a></li>
                    </ul>

                    <h3 class="text-xl font-bold mt-8 mb-4 text-slate-900">3. CLIA-Based Advanced Screening</h3>
                    <p>This is your premium preventive diagnostics layer.</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Explore Our Comprehensive Test Portfolio</a></li>
                    </ul>

                    <h3 class="text-xl font-bold mt-8 mb-4 text-slate-900">4. Clinical Chemistry Screening Parameters</h3>
                    <p>Used in routine health checkups.</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Explore Our Comprehensive Test Portfolio</a></li>
                    </ul>

                    <h3 class="text-xl font-bold mt-8 mb-4 text-slate-900">5. Urinalysis Screening</h3>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Explore Our Comprehensive Test Portfolio</a></li>
                    </ul>

                    <h3 class="text-xl font-bold mt-8 mb-4 text-slate-900">6. Instruments Supporting Preventive Screening</h3>
                    
                    <h4 class="font-semibold mt-4 text-slate-800">Core Screening Instruments</h4>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">ELISA Reader (Cypher)</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">ELISA Washer (Cypher)</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">CLIA Analyzer BI-1800</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">CLIA Analyzer Morpheus</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">CLIA Analyzer BI-2000</a></li>
                    </ul>

                    <h4 class="font-semibold mt-4 text-slate-800">Supporting Lab Systems</h4>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-200 / BI-280 / BI-380 / BI-480 / BI-600 Biochemistry Analyzers</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Trinity 3 / Trinity 5 Hematology Analyzers</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-210 / BI-220 / BI-240 ESR Analyzer</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-3000PT HbA1c Analyzer</a></li>
                    </ul>

                    <h4 class="font-semibold mt-4 text-slate-800">Public Health Screening</h4>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">NEO 12 Newborn Screening Analyzer</a></li>
                    </ul>

                    <h4 class="font-semibold mt-4 text-slate-800">POCT Devices (Preventive Use)</h4>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">BI-180 POCT Analyzer</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Haemoglobin Meter</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Creatinine Meter</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Lipid Testing System</a></li>
                    </ul>

                @elseif($solutionSlug === 'gem-portal-enablement')
                    <p>
                        Biogenix empowers its partners with direct access to government procurement opportunities through structured onboarding and enablement on the Government e-Marketplace (GeM) portal.
                    </p>
                    <p>
                        As an established and compliant brand on GeM, we extend our network by authorizing distributors to represent and sell Biogenix products across government institutions, public sector units, and healthcare programs. This initiative enables our partners to participate in high-value tenders and institutional procurement processes with confidence and credibility.
                    </p>
                    <p>
                        We provide end-to-end support to our authorized distributors, including product listing guidance, documentation assistance, pricing alignment, and operational coordination required for seamless participation on the GeM platform. Our team ensures that partners are equipped to navigate the procurement ecosystem efficiently while maintaining compliance with regulatory and platform requirements.
                    </p>
                    <p>
                        By leveraging Biogenix’s product portfolio, brand presence, and technical expertise, our distribution partners gain a strategic advantage in expanding their reach within the public healthcare sector. This model not only strengthens market penetration but also creates a scalable pathway for long-term growth through government-driven demand.
                    </p>
                    <p>
                        Biogenix remains committed to building a robust and reliable distribution network, enabling partners to unlock new business opportunities while delivering high-quality diagnostic solutions to institutions across the country.
                    </p>

                    <div class="mt-10">
                        <a href="{{ route('book-meeting') }}" class="inline-flex h-12 items-center justify-center rounded-2xl bg-primary-600 px-6 text-sm font-semibold text-white no-underline transition hover:bg-primary-700">
                            Book a Meeting
                        </a>
                    </div>
                @else
                    <p>
                        This page shares the overview, coverage areas, and implementation guidance for {{ $solutionName }}.
                        You can use it as the landing section for brochures, use-cases, and consultation steps.
                    </p>

                    <div class="mt-8 grid gap-4 md:grid-cols-2">
                        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <h2 class="text-lg font-semibold text-slate-900">Coverage Focus</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Structured workflows, equipment alignment, and deployment support across diagnostics teams.
                            </p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <h2 class="text-lg font-semibold text-slate-900">Implementation Support</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Planning templates, onboarding checklist, and outcome tracking for institutional teams.
                            </p>
                        </article>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
