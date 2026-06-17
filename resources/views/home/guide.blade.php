@extends('layouts.main')
@section('main')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h4>User Guide</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">User Guide</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid guide-wrap">

    {{-- Intro banner --}}
    <div class="guide-hero mb-4">
        <div class="guide-hero-icon">
            <i class="fa fa-book"></i>
        </div>
        <div>
            <h5 class="guide-hero-title">Cost Estimate — User Manual</h5>
            <p class="guide-hero-sub">PT Vale Indonesia, Tbk &nbsp;·&nbsp; Engineering &amp; Construction — Engineering Service</p>
        </div>
    </div>

    <div class="row g-3">

        {{-- Sidebar nav --}}
        <div class="col-lg-3">
            <div class="guide-nav-card">
                <div class="guide-nav-header">Contents</div>
                <ul class="guide-nav-list">
                    <li><a href="#overview"        class="guide-nav-link active"><i class="fa fa-circle-o me-2"></i>Overview</a></li>
                    <li><a href="#getting-started" class="guide-nav-link"><i class="fa fa-circle-o me-2"></i>Getting Started</a></li>
                    <li><a href="#tutorial"        class="guide-nav-link"><i class="fa fa-circle-o me-2"></i>Full Tutorial</a></li>
                    <li><a href="#tut-project"     class="guide-nav-link" style="padding-left:32px;"><i class="fa fa-caret-right me-2"></i>1. Create a Project</a></li>
                    <li><a href="#tut-wbs"         class="guide-nav-link" style="padding-left:32px;"><i class="fa fa-caret-right me-2"></i>2. WBS Structure</a></li>
                    <li><a href="#tut-work-item"   class="guide-nav-link" style="padding-left:32px;"><i class="fa fa-caret-right me-2"></i>3. Set Up a Work Item</a></li>
                    <li><a href="#tut-estimate"    class="guide-nav-link" style="padding-left:32px;"><i class="fa fa-caret-right me-2"></i>4. Build an Estimate</a></li>
                    <li><a href="#tut-approve"     class="guide-nav-link" style="padding-left:32px;"><i class="fa fa-caret-right me-2"></i>5. Approve &amp; Export</a></li>
                    <li><a href="#cost-estimate"   class="guide-nav-link"><i class="fa fa-circle-o me-2"></i>Cost Estimate Project</a></li>
                    <li><a href="#work-item"       class="guide-nav-link"><i class="fa fa-circle-o me-2"></i>Work Item</a></li>
                    <li><a href="#man-power"       class="guide-nav-link"><i class="fa fa-circle-o me-2"></i>Man Power</a></li>
                    <li><a href="#tools-equipment" class="guide-nav-link"><i class="fa fa-circle-o me-2"></i>Tools &amp; Equipment</a></li>
                    <li><a href="#material"        class="guide-nav-link"><i class="fa fa-circle-o me-2"></i>Material</a></li>
                    <li><a href="#approval"        class="guide-nav-link"><i class="fa fa-circle-o me-2"></i>Approval Workflow</a></li>
                    <li><a href="#export"          class="guide-nav-link"><i class="fa fa-circle-o me-2"></i>Export</a></li>
                    <li><a href="#roles"           class="guide-nav-link"><i class="fa fa-circle-o me-2"></i>Roles &amp; Permissions</a></li>
                </ul>
            </div>
        </div>

        {{-- Main content --}}
        <div class="col-lg-9">

            {{-- Overview --}}
            <div class="guide-section" id="overview">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#e8f0fb; color:#2e75b6;"><i class="fa fa-info-circle"></i></span>
                    <h5>Overview</h5>
                </div>
                <p>The <strong>Cost Estimate Web Application</strong> is a digital platform designed to replace the Excel-based cost estimation process used by the Engineering Project &amp; Services (EPS) department at PT Vale Indonesia. It allows engineering teams to create, manage, review, and approve project cost estimates in a structured and collaborative environment.</p>
                <div class="guide-highlight">
                    <strong>Key Benefits:</strong>
                    <ul class="mt-2 mb-0">
                        <li>Centralised database for man power rates, material prices, and equipment costs</li>
                        <li>Structured multi-discipline approval workflow</li>
                        <li>Real-time collaboration between engineers and reviewers</li>
                        <li>Automated Excel export for reporting</li>
                    </ul>
                </div>
            </div>

            {{-- Getting Started --}}
            <div class="guide-section" id="getting-started">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#e6f2df; color:#548235;"><i class="fa fa-play-circle"></i></span>
                    <h5>Getting Started</h5>
                </div>
                <p>After logging in you will land on the <strong>Dashboard</strong>, which shows a summary of all key data and your recent projects.</p>
                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="guide-step-num">1</div>
                        <div><strong>Check your role</strong> — your access level determines which modules and actions are available. Contact your administrator if a feature is not visible.</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">2</div>
                        <div><strong>Ensure master data is ready</strong> — Man Power rates, Material prices, and Tools &amp; Equipment rates must be approved before they can be used in a project estimate.</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">3</div>
                        <div><strong>Create a Cost Estimate project</strong> — go to <em>Cost Estimate</em> in the sidebar and click <strong>Create New Project</strong>.</div>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════
                 FULL TUTORIAL
            ══════════════════════════════════════════ --}}
            <div class="guide-section" id="tutorial">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#fff3e0; color:#e65c00;"><i class="fa fa-graduation-cap"></i></span>
                    <h5>Full Tutorial — From New Project to Excel Export</h5>
                </div>
                <p>This tutorial walks through the complete end-to-end flow: creating a project, defining the WBS, setting up work items with factorials and volumes, building the estimate, approving it, and downloading the final Excel file.</p>
                <div class="guide-highlight info">
                    <i class="fa fa-lightbulb-o me-2"></i><strong>Prerequisite:</strong> Make sure at least one approved Work Item, Man Power entry, Tool &amp; Equipment entry, and Material entry exist before building an estimate.
                </div>
            </div>

            {{-- Step 1 — Create Project --}}
            <div class="guide-section" id="tut-project">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#e8f0fb; color:#2e75b6;"><i class="fa fa-folder-open"></i></span>
                    <h5><span style="color:#9ca3af; font-weight:400; font-size:13px; margin-right:8px;">Step 1</span> Create a Cost Estimate Project</h5>
                </div>

                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="guide-step-num">1</div>
                        <div>In the sidebar click <strong>Cost Estimate</strong>. On the project list page click <strong>Create New Project</strong> (top-right button).</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">2</div>
                        <div>
                            Fill in the project header fields:
                            <div class="guide-table-wrap mt-2">
                                <table class="guide-table">
                                    <thead><tr><th>Field</th><th>Description</th></tr></thead>
                                    <tbody>
                                        <tr><td>Project No</td><td>Unique project number (e.g. <code>EPS-2025-001</code>)</td></tr>
                                        <tr><td>Project Title</td><td>Descriptive name of the project</td></tr>
                                        <tr><td>Area</td><td>Work location / plant area</td></tr>
                                        <tr><td>Project Manager</td><td>User responsible for final approval</td></tr>
                                        <tr><td>Project Engineer</td><td>Lead engineer (optional)</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">3</div>
                        <div>
                            Assign <strong>Design Engineers</strong> and <strong>Reviewers</strong> for each discipline that will be estimated (Civil, Mechanical, Electrical, Instrument, Architecture, IT). Leave unused disciplines blank.
                        </div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">4</div>
                        <div>Click <strong>Save</strong>. The project is created with status <span class="guide-badge draft">DRAFT</span> and you are taken to the project detail page.</div>
                    </div>
                </div>

                <div class="guide-highlight mt-3">
                    <i class="fa fa-copy me-2"></i>You can also <strong>Duplicate</strong> an existing project to reuse its WBS and estimates as a starting point.
                </div>
            </div>

            {{-- Step 2 — WBS --}}
            <div class="guide-section" id="tut-wbs">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#f3e8fb; color:#7030A0;"><i class="fa fa-sitemap"></i></span>
                    <h5><span style="color:#9ca3af; font-weight:400; font-size:13px; margin-right:8px;">Step 2</span> Define the WBS Structure</h5>
                </div>
                <p>The Work Breakdown Structure (WBS) organises estimate line items into a hierarchy. Each estimate discipline uses the WBS levels defined in the project. You must set up the WBS <em>before</em> adding work items to the estimate.</p>

                <h6 class="guide-sub-heading">Two ways to set up WBS</h6>
                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="guide-step-num">A</div>
                        <div>
                            <strong>Use the global WBS Setting</strong> (sidebar → <em>WBS Setting</em>) to define a reusable WBS library. These become available to all projects.<br>
                            Add Level-1 groups (e.g. <em>CIVIL WORKS</em>), then Level-2 sub-groups, and optionally Level-3 items. Drag to reorder.
                        </div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">B</div>
                        <div>
                            <strong>Customise per-project</strong> — inside the project detail page, open the <em>WBS</em> tab for a discipline, then add or reorder nodes specific to that estimate.
                        </div>
                    </div>
                </div>

                <h6 class="guide-sub-heading mt-3">WBS hierarchy</h6>
                <div class="guide-table-wrap">
                    <table class="guide-table">
                        <thead><tr><th>Level</th><th>Example</th><th>Purpose</th></tr></thead>
                        <tbody>
                            <tr><td><strong>Level 1</strong></td><td>CIVIL WORKS</td><td>Major category / phase</td></tr>
                            <tr><td><strong>Level 2</strong></td><td>FOUNDATION</td><td>Sub-category</td></tr>
                            <tr><td><strong>Level 3</strong></td><td>PILE CAP</td><td>Specific work package (work items are placed here)</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="guide-highlight info mt-3">
                    <i class="fa fa-info-circle me-2"></i>Work items are assigned to a <strong>Level-3</strong> node. If your WBS only has two levels, the Level-2 node acts as the lowest level.
                </div>
            </div>

            {{-- Step 3 — Work Item --}}
            <div class="guide-section" id="tut-work-item">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#fdeee3; color:#ED7D31;"><i class="fa fa-briefcase"></i></span>
                    <h5><span style="color:#9ca3af; font-weight:400; font-size:13px; margin-right:8px;">Step 3</span> Set Up a Work Item (with Factorial &amp; Volume)</h5>
                </div>
                <p>A Work Item is the standard unit of work that carries cost components. Before it can be used in an estimate it must be set up and reviewed.</p>

                <h6 class="guide-sub-heading">Create the Work Item</h6>
                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="guide-step-num">1</div>
                        <div>Go to <strong>Work Item → Work Item List</strong> and click <strong>Create New Work Item</strong>.</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">2</div>
                        <div>
                            Fill in the basic fields:
                            <div class="guide-table-wrap mt-2">
                                <table class="guide-table">
                                    <thead><tr><th>Field</th><th>Notes</th></tr></thead>
                                    <tbody>
                                        <tr><td>Category</td><td>Select the work item type / discipline category</td></tr>
                                        <tr><td>Description</td><td>Clear name for the unit of work, e.g. <em>Concrete Pouring — Grade 35</em></td></tr>
                                        <tr><td>Unit</td><td>Unit of measure: m³, m², m, unit, etc.</td></tr>
                                        <tr><td>Volume</td><td>Base volume for the unit rate calculation (usually 1)</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="guide-sub-heading mt-3">Add Man Power components</h6>
                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="guide-step-num">3</div>
                        <div>Open the <strong>Man Power</strong> sub-tab. Click <strong>Add Row</strong> and select a labour role from the database.</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">4</div>
                        <div>
                            Set the <strong>Quantity</strong> (number of workers) and the <strong>Hours</strong> required per unit volume.<br>
                            The system calculates: <code>Cost = Qty × Hours × Overall Hourly Rate</code>
                        </div>
                    </div>
                </div>

                <h6 class="guide-sub-heading mt-3">Add Tools &amp; Equipment components</h6>
                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="guide-step-num">5</div>
                        <div>Open the <strong>Tools &amp; Equipment</strong> sub-tab. Click <strong>Add Row</strong>, select the equipment, enter quantity and hours per unit.</div>
                    </div>
                </div>

                <h6 class="guide-sub-heading mt-3">Add Material components</h6>
                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="guide-step-num">6</div>
                        <div>Open the <strong>Material</strong> sub-tab. Click <strong>Add Row</strong>, select a material, enter the quantity required per unit of work.</div>
                    </div>
                </div>

                <h6 class="guide-sub-heading mt-3">Configure Factorial (output factor)</h6>
                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="guide-step-num">7</div>
                        <div>
                            The <strong>Factorial</strong> is a multiplier applied to the base cost of the work item to account for site conditions, complexity, or productivity factors.
                            <div class="guide-table-wrap mt-2">
                                <table class="guide-table">
                                    <thead><tr><th>Factorial</th><th>Effect</th></tr></thead>
                                    <tbody>
                                        <tr><td><code>1.0</code></td><td>No adjustment (standard conditions)</td></tr>
                                        <tr><td><code>1.2</code></td><td>20 % increase (e.g. difficult access)</td></tr>
                                        <tr><td><code>0.8</code></td><td>20 % decrease (e.g. pre-fabricated components)</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            Enter the factorial value in the designated field on the work item form. It is saved with the work item and can be overridden when the item is placed in an estimate.
                        </div>
                    </div>
                </div>

                <h6 class="guide-sub-heading mt-3">Submit for review</h6>
                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="guide-step-num">8</div>
                        <div>Click <strong>Submit</strong>. A reviewer must change the status to <span class="guide-badge reviewed">REVIEWED</span> before the work item can be used in any estimate.</div>
                    </div>
                </div>

                <div class="guide-highlight mt-3">
                    <i class="fa fa-info-circle me-2"></i>A work item in <span class="guide-badge draft">DRAFT</span> status will not appear in the estimate work item picker.
                </div>
            </div>

            {{-- Step 4 — Build Estimate --}}
            <div class="guide-section" id="tut-estimate">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#e8eaf6; color:#3949ab;"><i class="fa fa-calculator"></i></span>
                    <h5><span style="color:#9ca3af; font-weight:400; font-size:13px; margin-right:8px;">Step 4</span> Build a Discipline Estimate</h5>
                </div>
                <p>With the project, WBS, and work items ready, you can now build the actual cost estimate for each discipline.</p>

                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="guide-step-num">1</div>
                        <div>Open the project detail page. Select the discipline tab you are assigned to (e.g. <strong>Civil</strong>). Click <strong>Create Estimate</strong>.</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">2</div>
                        <div>The estimate opens in edit mode. The left panel shows the <strong>WBS tree</strong>. Click a WBS Level-3 node to make it the active scope.</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">3</div>
                        <div>Click <strong>Add Work Item</strong>. A picker opens listing all <span class="guide-badge reviewed">REVIEWED</span> work items. Search by description or category, then click <strong>Add</strong>.</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">4</div>
                        <div>
                            For each added work item, set:
                            <div class="guide-table-wrap mt-2">
                                <table class="guide-table">
                                    <thead><tr><th>Field</th><th>Description</th></tr></thead>
                                    <tbody>
                                        <tr><td><strong>Volume</strong></td><td>Actual quantity of this work in the project (e.g. 120 m³ of concrete)</td></tr>
                                        <tr><td><strong>Factorial</strong></td><td>Override the default factorial if site conditions differ. Leave blank to use the work item default.</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            The system recalculates totals immediately: <code>Total Cost = Unit Cost × Volume × Factorial</code>
                        </div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">5</div>
                        <div>Repeat for all WBS nodes and all disciplines. Each discipline estimate is saved independently.</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">6</div>
                        <div>When the estimate is complete, click <strong>Submit / Publish</strong>. The status changes to <span class="guide-badge" style="background:#e8eaf6;color:#3949ab;">PENDING REVIEW</span> and the assigned reviewer receives an email and in-app notification.</div>
                    </div>
                </div>

                <div class="guide-highlight info mt-3">
                    <i class="fa fa-lightbulb-o me-2"></i>You can save and return to an estimate at any time while it is in <span class="guide-badge draft">DRAFT</span> status. Once submitted, editing is locked until the reviewer rejects it back.
                </div>
            </div>

            {{-- Step 5 — Approve & Export --}}
            <div class="guide-section" id="tut-approve">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#e8f5e9; color:#2e7d32;"><i class="fa fa-check-circle"></i></span>
                    <h5><span style="color:#9ca3af; font-weight:400; font-size:13px; margin-right:8px;">Step 5</span> Approve &amp; Download Excel</h5>
                </div>

                <h6 class="guide-sub-heading">Discipline Reviewer actions</h6>
                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="guide-step-num">1</div>
                        <div>The reviewer opens the project and navigates to their discipline tab. They review each line item and can add <strong>Review Notes</strong> (comments) for the engineer.</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">2</div>
                        <div>Click <strong>Approve</strong> to approve the discipline estimate, or <strong>Reject</strong> to send it back to the engineer with notes. The engineer is notified by email and in-app notification.</div>
                    </div>
                </div>

                <h6 class="guide-sub-heading mt-3">All disciplines approved → Project Manager</h6>
                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="guide-step-num">3</div>
                        <div>Once <strong>all assigned discipline reviewers</strong> have approved, the project status advances to <strong>Pending PM Approval</strong>. The Project Manager receives a notification.</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">4</div>
                        <div>The Project Manager reviews the full cost summary and clicks <strong>Approve</strong>. The project status changes to <span class="guide-badge approved">APPROVED</span>. All design engineers receive an email with the PDF export attached.</div>
                    </div>
                </div>

                <h6 class="guide-sub-heading mt-3">Download Excel</h6>
                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="guide-step-num">5</div>
                        <div>Once the project is <span class="guide-badge approved">APPROVED</span>, the <strong>Export Excel</strong> button is active on the project detail page. Click it to download the full cost estimate breakdown as a formatted Excel file.</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">6</div>
                        <div>
                            The Excel export includes:
                            <ul class="mt-1 mb-0" style="font-size:13px; padding-left:18px;">
                                <li>Cover sheet with project details</li>
                                <li>Summary sheet with totals per discipline</li>
                                <li>One detail sheet per discipline — WBS breakdown, work items, volumes, factorials, man power, equipment, and material costs</li>
                                <li>Grand total with contingency applied</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="guide-highlight mt-3">
                    <i class="fa fa-info-circle me-2"></i>Export is only available to users with the <span class="guide-badge" style="background:#e8eaf6;color:#3949ab;">export</span> permission. Contact your administrator if the button is not visible.
                </div>
            </div>

            {{-- Cost Estimate Project reference --}}
            <div class="guide-section" id="cost-estimate">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#e8f0fb; color:#2e75b6;"><i class="fa fa-folder-open"></i></span>
                    <h5>Cost Estimate Project — Reference</h5>
                </div>
                <p>A Cost Estimate Project groups all discipline estimates (Civil, Mechanical, Electrical, Instrument, IT, Architecture) under a single project number.</p>
                <p>Use the <strong>Duplicate</strong> function to create a copy of an existing project. The WBS structure and all estimate line items are cloned; only the project header (No, Title, Area, engineers) needs to be updated.</p>
                <div class="guide-highlight info">
                    <i class="fa fa-lightbulb-o me-2"></i>Contingency is configured in <strong>Project Settings</strong> (gear icon on the project detail page) and is added to the grand total at export time.
                </div>
            </div>

            {{-- Work Item --}}
            <div class="guide-section" id="work-item">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#fdeee3; color:#ED7D31;"><i class="fa fa-briefcase"></i></span>
                    <h5>Work Item</h5>
                </div>
                <p>Work Items are the standard building blocks of a cost estimate. Each work item defines a unit of work with associated man power, tools &amp; equipment, and material costs.</p>
                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="guide-step-num">1</div>
                        <div>Go to <strong>Work Item → Work Item List</strong> and click <strong>Create New Work Item</strong>.</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">2</div>
                        <div>Select the category, enter the description, volume, and unit.</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">3</div>
                        <div>Add Man Power, Tools &amp; Equipment, and Material components using the sub-item tabs.</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-step-num">4</div>
                        <div>Submit for review. A reviewer must set it to <span class="guide-badge reviewed">REVIEWED</span> before it can be used in projects.</div>
                    </div>
                </div>
            </div>

            {{-- Man Power --}}
            <div class="guide-section" id="man-power">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#e6f2df; color:#548235;"><i class="fa fa-users"></i></span>
                    <h5>Man Power</h5>
                </div>
                <p>The Man Power module stores labour rates for each skill level. These rates are referenced when calculating the man power cost component of a Work Item.</p>
                <div class="guide-table-wrap">
                    <table class="guide-table">
                        <thead><tr><th>Field</th><th>Description</th></tr></thead>
                        <tbody>
                            <tr><td>Code</td><td>Unique identifier for the man power entry</td></tr>
                            <tr><td>Skill Level</td><td>Classification (e.g. Unskilled, Skilled, Supervisor)</td></tr>
                            <tr><td>Title</td><td>Job title or role description</td></tr>
                            <tr><td>Basic Rate Monthly</td><td>Monthly base salary in IDR</td></tr>
                            <tr><td>Basic Rate Hour</td><td>Hourly rate derived from monthly rate</td></tr>
                            <tr><td>Overall Rate Hourly</td><td>All-in hourly rate including overhead factors</td></tr>
                        </tbody>
                    </table>
                </div>
                <p class="mt-3">Use <strong>Import</strong> to bulk-upload man power data from the provided Excel template. Use <strong>Export</strong> to download the current database.</p>
            </div>

            {{-- Tools & Equipment --}}
            <div class="guide-section" id="tools-equipment">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#f3e8fb; color:#7030A0;"><i class="fa fa-wrench"></i></span>
                    <h5>Tools &amp; Equipment</h5>
                </div>
                <p>The Tools &amp; Equipment module stores equipment rental or usage rates. Entries are grouped by category and can be assigned to Work Items as cost components.</p>
                <p>Both <strong>Local Rate</strong> (IDR) and <strong>National Rate</strong> are stored to allow comparison. Use Import/Export for bulk management.</p>
            </div>

            {{-- Material --}}
            <div class="guide-section" id="material">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#fff8e1; color:#b8860b;"><i class="fa fa-truck"></i></span>
                    <h5>Material</h5>
                </div>
                <p>The Material module stores material unit prices referenced in Work Items. Each material entry includes a stock code, reference material number, unit, and rate.</p>
                <p>Material Category must be set up first before creating individual material entries. Use Import to upload materials from the Excel template.</p>
            </div>

            {{-- Approval Workflow --}}
            <div class="guide-section" id="approval">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#e8eaf6; color:#3949ab;"><i class="fa fa-check-circle"></i></span>
                    <h5>Approval Workflow</h5>
                </div>
                <p>Cost Estimate projects follow a multi-stage approval process before they are finalised.</p>

                <div class="guide-flow">
                    <div class="guide-flow-step">
                        <div class="guide-flow-icon" style="background:#e8f0fb; color:#2e75b6;"><i class="fa fa-pencil"></i></div>
                        <div class="guide-flow-label">DRAFT</div>
                        <div class="guide-flow-desc">Project created and estimates being built</div>
                    </div>
                    <div class="guide-flow-arrow"><i class="fa fa-arrow-right"></i></div>
                    <div class="guide-flow-step">
                        <div class="guide-flow-icon" style="background:#fff3e0; color:#e65c00;"><i class="fa fa-paper-plane"></i></div>
                        <div class="guide-flow-label">SUBMITTED</div>
                        <div class="guide-flow-desc">Sent to discipline reviewers</div>
                    </div>
                    <div class="guide-flow-arrow"><i class="fa fa-arrow-right"></i></div>
                    <div class="guide-flow-step">
                        <div class="guide-flow-icon" style="background:#e8eaf6; color:#3949ab;"><i class="fa fa-search"></i></div>
                        <div class="guide-flow-label">DISCIPLINE REVIEW</div>
                        <div class="guide-flow-desc">Each assigned discipline engineer approves their section</div>
                    </div>
                    <div class="guide-flow-arrow"><i class="fa fa-arrow-right"></i></div>
                    <div class="guide-flow-step">
                        <div class="guide-flow-icon" style="background:#e8f5e9; color:#2e7d32;"><i class="fa fa-check"></i></div>
                        <div class="guide-flow-label">APPROVED</div>
                        <div class="guide-flow-desc">Project Manager final approval</div>
                    </div>
                </div>

                <div class="guide-highlight info mt-3">
                    <i class="fa fa-envelope me-2"></i>Email notifications and in-app notifications are sent automatically at each approval stage to the relevant reviewers and the project creator.
                </div>
            </div>

            {{-- Export --}}
            <div class="guide-section" id="export">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#e8f5e9; color:#2e7d32;"><i class="fa fa-file-excel-o"></i></span>
                    <h5>Export</h5>
                </div>
                <p>Most modules support Excel export. Click the <strong>Export Excel</strong> button on any list page to download the current data.</p>
                <div class="guide-table-wrap">
                    <table class="guide-table">
                        <thead><tr><th>Module</th><th>Export Content</th></tr></thead>
                        <tbody>
                            <tr><td>Cost Estimate Summary</td><td>Full estimate breakdown per discipline with totals</td></tr>
                            <tr><td>Work Item</td><td>All work items with man power, equipment, and material detail</td></tr>
                            <tr><td>Man Power</td><td>Complete labour rate database</td></tr>
                            <tr><td>Tools &amp; Equipment</td><td>Equipment rates by category</td></tr>
                            <tr><td>Material</td><td>Material catalogue with rates</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="guide-highlight mt-3">
                    <i class="fa fa-info-circle me-2"></i>Export access is role-controlled. Contact your administrator if the Export button is not visible.
                </div>
            </div>

            {{-- Roles --}}
            <div class="guide-section" id="roles">
                <div class="guide-section-header">
                    <span class="guide-section-icon" style="background:#f0f4ff; color:#3949ab;"><i class="fa fa-shield"></i></span>
                    <h5>Roles &amp; Permissions</h5>
                </div>
                <p>Access in the system is controlled by roles assigned per feature and action. Contact your system administrator to request role changes.</p>
                <div class="guide-table-wrap">
                    <table class="guide-table">
                        <thead><tr><th>Role / Action</th><th>Description</th></tr></thead>
                        <tbody>
                            <tr><td><span class="guide-badge" style="background:#e8f0fb;color:#2e75b6;">read</span></td><td>View list and detail pages</td></tr>
                            <tr><td><span class="guide-badge" style="background:#e6f2df;color:#548235;">create</span></td><td>Create new records</td></tr>
                            <tr><td><span class="guide-badge" style="background:#fff8e1;color:#b8860b;">update</span></td><td>Edit existing records</td></tr>
                            <tr><td><span class="guide-badge" style="background:#fce4ec;color:#c62828;">delete</span></td><td>Remove records</td></tr>
                            <tr><td><span class="guide-badge" style="background:#e8eaf6;color:#3949ab;">export</span></td><td>Download Excel exports</td></tr>
                            <tr><td><span class="guide-badge" style="background:#f3e8fb;color:#7030A0;">import</span></td><td>Upload data via Excel</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
