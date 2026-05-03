<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>HATS_REPORT_{{ strtoupper($type) }}_{{ now()->format('Ymd') }}</title>
    <style>
        @page { 
            margin: 1.5cm 1.2cm; 
        }
        
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #334155; 
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-size: 9pt;
            line-height: 1.5;
        }

        /* Sophisticated Minimalist Header */
        .header {
            border-top: 5px solid #4f46e5;
            padding-top: 20px;
            margin-bottom: 40px;
            width: 100%;
        }
        
        .brand-section { float: left; width: 60%; }
        .brand-text { font-size: 18pt; font-weight: 700; color: #0f172a; letter-spacing: -1px; margin: 0; }
        .brand-text span { color: #4f46e5; }
        .tagline { font-size: 7.5pt; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; margin-top: 2px; }
        
        .meta-section { float: right; width: 40%; text-align: right; }
        .report-title { font-size: 10pt; font-weight: 700; color: #1e293b; text-transform: uppercase; margin-bottom: 4px; }
        .report-date { font-size: 8pt; color: #64748b; font-weight: 500; }

        .clear { clear: both; }

        /* Content Sections */
        .content { margin-top: 10px; }

        /* Refined Metadata Grid */
        .meta-grid {
            width: 100%;
            margin-bottom: 35px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 20px;
        }
        .meta-td { width: 25%; vertical-align: top; }
        .label { font-size: 7pt; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 2px; }
        .value { font-size: 9pt; font-weight: 700; color: #475569; }

        /* Unified Section Styling */
        .section { margin-bottom: 40px; page-break-inside: avoid; }
        .section-title {
            font-size: 11pt;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            border-left: 4px solid #4f46e5;
            padding: 5px 15px;
            margin-bottom: 20px;
            background: #f8fafc;
            letter-spacing: 0.5px;
        }

        /* Metric Cards */
        .metric-table { 
            width: 100%; 
            margin-bottom: 15px; 
            border-collapse: collapse;
        }
        .metric-cell {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            padding: 12px 15px;
            width: 32%;
            border-radius: 4px;
        }
        .metric-label { font-size: 7pt; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 2px; }
        .metric-value { font-size: 13pt; font-weight: 700; color: #0f172a; }

        /* Data Tables */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            text-align: left;
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            padding: 10px 15px;
            border-bottom: 2px solid #f1f5f9;
            background: #ffffff;
        }
        .data-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #f8fafc;
            color: #334155;
            font-size: 8.5pt;
        }
        .data-table tr:nth-child(even) { background-color: #fafafa; }
        
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier', monospace; font-size: 8.5pt; }
        .text-indigo { color: #4f46e5; font-weight: 700; }
        .text-success { color: #059669; font-weight: 700; }

        /* Formal Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding-top: 15px;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            font-size: 7.5pt;
            color: #94a3b8;
        }

        .sig-section { margin-top: 50px; width: 100%; }
        .sig-box { width: 45%; border-top: 1px solid #e2e8f0; padding-top: 8px; font-size: 8pt; font-weight: 700; text-align: center; color: #64748b; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand-section">
            <h1 class="brand-text">HATS <span>HRMS</span></h1>
            <div class="tagline">Workforce Intelligence & Operations</div>
        </div>
        <div class="meta-section">
            <div class="report-title">{{ $type === 'overview' ? 'Executive Overview' : ucfirst($type) . ' Verification' }}</div>
            <div class="report-date">Issued: {{ now()->format('d M Y') }}</div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="content">
        <table class="meta-grid">
            <tr>
                <td class="meta-td">
                    <div class="label">Reference</div>
                    <div class="value">#{{ strtoupper(substr(md5(now()), 0, 8)) }}</div>
                </td>
                <td class="meta-td">
                    <div class="label">Authorized By</div>
                    <div class="value">{{ auth()->user()->name }}</div>
                </td>
                <td class="meta-td">
                    <div class="label">Classification</div>
                    <div class="value">Confidential</div>
                </td>
                <td class="meta-td" style="text-align: right;">
                    <div class="label">Security ID</div>
                    <div class="value">G-AUD-{{ now()->format('Y') }}</div>
                </td>
            </tr>
        </table>

        @php
            $sections = ($type === 'overview') ? ['workforce', 'attendance', 'leaves', 'payroll', 'performance'] : [$type];
        @endphp

        @foreach($sections as $section)
            @php 
                $sectionData = ($type === 'overview') ? ($data[$section] ?? []) : $data; 
                $sectionName = match($section) {
                    'workforce' => 'Personnel Composition',
                    'attendance' => 'Attendance Analytics',
                    'leaves' => 'Leave Distributions',
                    'payroll' => 'Financial Summary',
                    'performance' => 'Performance Tiers',
                    default => ucfirst($section)
                };
            @endphp

            @if(!empty($sectionData))
            <div class="section">
                <div class="section-title">{{ $sectionName }}</div>

                @if($section === 'attendance')
                    @php 
                        $total = collect($sectionData['summary']['values'] ?? [])->sum();
                        $presentIdx = array_search('Present', $sectionData['summary']['labels'] ?? []);
                        $present = $presentIdx !== false ? $sectionData['summary']['values'][$presentIdx] : 0;
                    @endphp
                    <table class="metric-table">
                        <tr>
                            <td class="metric-cell">
                                <div class="metric-label">Total Records</div>
                                <div class="metric-value">{{ $total }}</div>
                            </td>
                            <td width="2%"></td>
                            <td class="metric-cell">
                                <div class="metric-label">Active Presence</div>
                                <div class="metric-value">{{ $present }}</div>
                            </td>
                            <td width="2%"></td>
                            <td class="metric-cell">
                                <div class="metric-label">Reliability</div>
                                <div class="metric-value text-indigo">{{ $total > 0 ? round(($present / $total) * 100, 1) : 0 }}%</div>
                            </td>
                        </tr>
                    </table>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th class="text-right">Units</th>
                                <th class="text-right">Share</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sectionData['summary']['labels'] ?? [] as $i => $label)
                            <tr>
                                <td style="font-weight: 700;">{{ $label }}</td>
                                <td class="text-right">{{ $sectionData['summary']['values'][$i] }}</td>
                                <td class="text-right font-mono text-indigo">{{ $total > 0 ? round(($sectionData['summary']['values'][$i] / $total) * 100, 1) : 0 }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                @elseif($section === 'workforce')
                    <table class="metric-table">
                        <tr>
                            <td class="metric-cell">
                                <div class="metric-label">Total Personnel</div>
                                <div class="metric-value">{{ $sectionData['total'] ?? 0 }}</div>
                            </td>
                            <td width="2%"></td>
                            <td class="metric-cell">
                                <div class="metric-label">Business Units</div>
                                <div class="metric-value">{{ count($sectionData['labels'] ?? []) }}</div>
                            </td>
                            <td width="34%"></td>
                        </tr>
                    </table>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Department / Unit</th>
                                <th class="text-right">Staff Count</th>
                                <th class="text-right">Org Share</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sectionData['labels'] ?? [] as $i => $label)
                            <tr>
                                <td style="font-weight: 700;">{{ $label }}</td>
                                <td class="text-right">{{ $sectionData['values'][$i] }}</td>
                                <td class="text-right font-mono text-indigo">{{ $sectionData['total'] > 0 ? round(($sectionData['values'][$i] / $sectionData['total']) * 100, 1) : 0 }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                @elseif($section === 'payroll')
                    @php $totalPay = collect($sectionData['values'] ?? [])->sum(); @endphp
                    <table class="metric-table">
                        <tr>
                            <td class="metric-cell" style="width: 45%;">
                                <div class="metric-label">Total Net Disbursement</div>
                                <div class="metric-value text-success">Php {{ number_format($totalPay, 2) }}</div>
                            </td>
                            <td width="55%"></td>
                        </tr>
                    </table>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Cost Center</th>
                                <th class="text-right">Amount (Net)</th>
                                <th class="text-right">Share</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sectionData['labels'] ?? [] as $i => $label)
                            <tr>
                                <td style="font-weight: 700;">{{ $label }}</td>
                                <td class="text-right text-success font-mono">Php {{ number_format($sectionData['values'][$i], 2) }}</td>
                                <td class="text-right font-mono text-indigo">{{ $totalPay > 0 ? round(($sectionData['values'][$i] / $totalPay) * 100, 1) : 0 }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                @elseif($section === 'leaves')
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Classification</th>
                                <th class="text-right">Approved Instances</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sectionData['labels'] ?? [] as $i => $label)
                            <tr>
                                <td style="font-weight: 700;">{{ $label }}</td>
                                <td class="text-right">{{ $sectionData['values'][$i] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                @elseif($section === 'performance')
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Rating Tier</th>
                                <th class="text-right">Frequency</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sectionData['labels'] ?? [] as $i => $label)
                            <tr>
                                <td style="font-weight: 700;">{{ $label }}</td>
                                <td class="text-right">{{ $sectionData['values'][$i] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            @endif
        @endforeach

        <table class="sig-section">
            <tr>
                <td class="sig-box">Administrative Audit</td>
                <td width="10%"></td>
                <td class="sig-box">System Certification</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        HATS Global Operations Center &bull; {{ now()->format('Y') }} Audit Cycle &bull; Generated: {{ now()->format('Y-m-d H:i') }}
    </div>
</body>
</html>
