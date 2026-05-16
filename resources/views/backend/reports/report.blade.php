@extends('backend.layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Reports & Analytics</h1>
        </div>
    </div>
    <div class="row justify-content-end py-2">
        <div class="col-12">
            <form class="form-box filter-options">
                <div class="row justify-content-center filters">
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
                        <x-select-input name="status" icon="bi-check2-circle" label="Year"
                            :options="statusoptions()" placeholder="Select Year" />
                    </div>
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3">        
                        <x-select-input name="status" icon="bi-check2-circle" label="Round"
                            :options="statusoptions()" placeholder="Select Round" />
                    </div>
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3">        
                        <x-select-input name="status" icon="bi-check2-circle" label="Reviewer"
                            :options="statusoptions()" placeholder="Select Category" />
                    </div>   
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3">        
                        <x-select-input name="status" icon="bi-check2-circle" label="Reviewer"
                            :options="statusoptions()" placeholder="Select Gender" />
                    </div>    
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3">   
                        <x-text-input name="title" type="date" placeholder="Select Date Range" label="Select Date Range"
                            icon="bi-card-checklist" autocomplete="Select Date Range" :required="true" />
                    </div>
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3">        
                        <button type="submit" class="cms-btn rounded-2 px-5">Search</button>
                    </div>    
                </div>
            </form>
        </div>
    </div>
    <div class="report-card-box pb-2 pt-3">
        <div class="row">
            <div class="col-xl-3 col-md-6 col-sm-6 col-12 mb-4">
                <a href="#">
                    <div class="report-card rounded-4 h-100 d-flex flex-column justify-content-center">
                        <p class="fw-bold text-black border-bottom border-2 pb-3 text-center text-capitalize"><i class="bi bi-person-vcard me-2" aria-hidden="true"></i> Total Applications</p>
                        <span class="text-blue pt-2 d-block text-center fs-1">1,250</span>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6 col-sm-6 col-12 mb-4">
                <a href="#">
                    <div class="report-card rounded-4 h-100 d-flex flex-column justify-content-center">
                        <p class="fw-bold text-black border-bottom border-2 pb-3 text-center text-capitalize"><i class="bi bi-award me-2" aria-hidden="true"></i> Awarded Applications</p>
                        <span class="text-success pt-2 d-block text-center fs-1">420</span>
                        <!-- <a href="#" class="report-btn fw-semibold rounded-3 d-flex justify-content-center align-items-center gap-2 my-2"><i class="bi bi-file-earmark-text" aria-hidden="true"></i> Generate Provisional Letter</a>
                        <a href="#" class="report-btn black-btn fw-semibold rounded-3 d-flex justify-content-center align-items-center gap-2"><i class="bi bi-eye" aria-hidden="true"></i> View Provisional letter</a> -->
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6 col-sm-6 col-12 mb-4">
                <a href="#">
                    <div class="report-card rounded-4 h-100 d-flex flex-column justify-content-center">
                        <p class="fw-bold text-black border-bottom border-2 pb-3 text-center text-capitalize"><i class="bi bi-clipboard-x me-2" aria-hidden="true"></i> Rejected Applications</p>
                        <span class="text-danger pt-2 d-block text-center fs-1">330</span>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6 col-sm-6 col-12 mb-4">
                <a href="#">
                    <div class="report-card rounded-4 h-100 d-flex flex-column justify-content-center">
                        <p class="fw-bold text-black border-bottom border-2 pb-3 text-center text-capitalize"><i class="bi bi-clipboard2-data me-2" aria-hidden="true"></i> Award Rate (%)</p>
                        <span class="text-blue pt-2 d-block text-center fs-1">33.4%</span>
                    </div>
                </a>
            </div>
        </div>
    </div>  
    <div class="row py-2 justify-content-center">
        <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-12 mb-3">
            <div class="status-card bg-white rounded-2 h-100">
                <div class="chart-heading d-flex align-items-center justify-content-between mb-3">
                    <h2 class="text-blue text-center fw-bold mb-0">Status Summary</h2>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" data-bs-toggle="dropdown"><i class="bi bi-list" aria-hidden="true"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" onclick="exportSinglePDF('status')">PDF</a></li>
                            <li><a class="dropdown-item" onclick="exportSingleExcel('status')">Excel</a></li>
                            <li><a class="dropdown-item" onclick="exportSingleCSV('status')">CSV</a></li>
                            <li><a class="dropdown-item" onclick="printSingleChart('status')">Print</a></li>
                        </ul>
                    </div>
                </div>
                <div class="chart-container p-2">
                    <canvas id="statusChart" class="status-box"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-12 mb-3">
            <div class="status-card bg-white rounded-2 h-100">
                <div class="chart-heading d-flex align-items-center justify-content-between mb-3">
                    <h2 class="text-blue text-center fw-bold mb-0">Year Round-wise Summary</h2>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" data-bs-toggle="dropdown"><i class="bi bi-list" aria-hidden="true"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" onclick="exportSinglePDF('year')">PDF</a></li>
                            <li><a class="dropdown-item" onclick="exportSingleExcel('year')">Excel</a></li>
                            <li><a class="dropdown-item" onclick="exportSingleCSV('year')">CSV</a></li>
                            <li><a class="dropdown-item" onclick="printSingleChart('year')">Print</a></li>
                        </ul>
                    </div>
                </div>
                <div class="chart-container p-3">
                    <canvas id="yearChart"></canvas>
                </div>
            </div>
        </div>
         <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-12 mb-3">
            <div class="status-card bg-white rounded-2 h-100">
                <div class="chart-heading d-flex align-items-center justify-content-between mb-3">
                    <h2 class="text-blue text-center fw-bold mb-0">Gender Distribution</h2>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" data-bs-toggle="dropdown"><i class="bi bi-list" aria-hidden="true"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" onclick="exportSinglePDF('gender')">PDF</a></li>
                            <li><a class="dropdown-item" onclick="exportSingleExcel('gender')">Excel</a></li>
                            <li><a class="dropdown-item" onclick="exportSingleCSV('gender')">CSV</a></li>
                            <li><a class="dropdown-item" onclick="printSingleChart('gender')">Print</a></li>
                        </ul>
                    </div>
                </div>
                <div class="chart-container p-3">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3">
            <div class="d-flex align-items-center gap-2">
                <button class="btns btn-excel px-5 py-2 rounded-2 text-white fw-semibold" onclick="exportExcel()">Excel</button>
                <button class="btns btn-pdf px-5 py-2 rounded-2 text-white fw-semibold" onclick="exportPDF()">PDF</button>
                <button class="btns btn-csv px-5 py-2 rounded-2 text-black fw-semibold" onclick="exportCSV()">CSV</button>
                <button class="btns btn-print px-5 py-2 rounded-2 text-white fw-semibold" onclick="printCharts()">Print</button>
            </div>
        </div>
    </div>  
    <div class="row py-2">
        <div class="col-12 mb-3">
            <div class="status-card bg-white rounded-2 h-100">
                <h2 class="text-blue text-center fw-bold">6.3 Detailed Tabular View</h2>
                <div class="table-responsive p-3">
                    <table class="table table-bordered table-data text-center mb-0">
                        <thead>
                            <tr>
                                <th scope="col">S.No</th>
                                <th scope="col">Year</th>
                                <th scope="col">Round</th>
                                <th scope="col">Category</th>
                                <th scope="col">Gender</th>
                                <th scope="col">Total Applications</th>
                                <th scope="col">Awarded</th>
                                <th scope="col">Rejected</th>
                                <th scope="col">Award Rate (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>2021</td>
                                <td>Round 1</td>
                                <td>Abc</td>
                                <td>Male</td>
                                <td>300</td>
                                <td>120</td>
                                <td>150</td>
                                <td>40%</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>2021</td>
                                <td>Round 1</td>
                                <td>Abc</td>
                                <td>Male</td>
                                <td>300</td>
                                <td>120</td>
                                <td>150</td>
                                <td>40%</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>2021</td>
                                <td>Round 1</td>
                                <td>Abc</td>
                                <td>Male</td>
                                <td>300</td>
                                <td>120</td>
                                <td>150</td>
                                <td>40%</td>
                            </tr>
                            <tr>
                                <th scope="row">4</th>
                                <td>2021</td>
                                <td>Round 1</td>
                                <td>Abc</td>
                                <td>Male</td>
                                <td>300</td>
                                <td>120</td>
                                <td>150</td>
                                <td>40%</td>
                            </tr>
                            <tr>
                                <th scope="row">5</th>    
                                <td>2021</td>
                                <td>Round 1</td>
                                <td>Abc</td>
                                <td>Male</td>
                                <td>300</td>
                                <td>120</td>
                                <td>150</td>
                                <td>40%</td>
                            </tr>
                        </tbody>
                    </table>   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    Chart.register(ChartDataLabels);
/* ================= PIE CHART ================= */
    const statusChart = new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: ['Awarded', 'Rejected', 'Pending'],
            datasets: [{
                data: [42, 25, 33],
                backgroundColor: ['#00305e', '#b1120e', '#ff9f1c'],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        boxWidth: 14,
                        padding: 15,
                    }
                },

                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold', size: 15 },
                    formatter: (value, ctx) => {
                        let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        return Math.round((value / sum) * 100) + '%';
                    }
                }
            }
        }
    });

/* ================= BAR CHART – YEAR ================= */

    const yearChart = new Chart(document.getElementById('yearChart'), {
        type: 'bar',
        data: {
            labels: ['2021', '2022', '2023', '2024'],
            datasets: [
                {
                    label: 'Round 1',
                    data: [500, 800, 900, 800],
                    backgroundColor: '#00305e'
                },
                {
                    label: 'Round 2',
                    data: [700, 600, 355, 500],
                    backgroundColor: '#ff9f1c'
                },
                {
                    label: 'Round 3',
                    data: [400, 671, 704, 750],
                    backgroundColor: '#015227'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,   
                        pointStyle: 'rect',
                        boxWidth: 12,
                        color: '#333'
                    }
                },

                datalabels: {
                    display: false   
                }
            }
        }
    });

/* ================= BAR CHART – GENDER ================= */
    const genderChart = new Chart(document.getElementById('genderChart'), {
        type: 'bar',
        data: {
            labels: ['Male', 'Female', 'Transgender'],
            datasets: [{
                label: 'Count',
                data: [18, 15, 9],
                backgroundColor: ['#00305e', '#015227', '#ff9f1c'],
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,     
                        pointStyle: 'rect',
                        boxWidth: 12,
                        color: '#333'
                    }
                },

                datalabels: {
                    display: false   
                }
            }
        }
    });
</script>
<script>
/* ==============================
   CHART MAP (IMPORTANT)
============================== */
const chartMap = {
    status: statusChart,
    year: yearChart,
    gender: genderChart
};


/* ==============================
   SINGLE CHART EXPORT (DROPDOWN)
============================== */

/* ---- PDF (single chart) ---- */
function exportSinglePDF(type) {
    const chart = chartMap[type];
    if (!chart) return;

    const container = chart.canvas.parentElement;

    html2canvas(container, { scale: 2 }).then(canvas => {
        const pdf = new jspdf.jsPDF();
        pdf.text(type.toUpperCase() + " SUMMARY", 15, 15);
        pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 15, 20, 180, 120);
        pdf.save(type + '-chart.pdf');
    });
}

/* ---- Excel (single chart) ---- */
function exportSingleExcel(type) {
    const chart = chartMap[type];
    let html = `<table border="1">
        <tr><th colspan="2">${type.toUpperCase()} Summary</th></tr>
        <tr><th>Label</th><th>Value</th></tr>`;

    chart.data.labels.forEach((l, i) => {
        html += `<tr><td>${l}</td><td>${chart.data.datasets[0].data[i]}</td></tr>`;
    });

    html += `</table>`;
    downloadFile(html, 'application/vnd.ms-excel', type + '.xls');
}

/* ---- CSV (single chart) ---- */
function exportSingleCSV(type) {
    const chart = chartMap[type];
    let csv = "Label,Value\n";

    chart.data.labels.forEach((l, i) => {
        csv += `${l},${chart.data.datasets[0].data[i]}\n`;
    });

    downloadFile(csv, 'text/csv', type + '.csv');
}
/* ---- Print (single chart) ---- */
// function printSingleChart(type) {
//     const chart = chartMap[type];
//     const win = window.open('');
//     win.document.write(chart.canvas.parentElement.innerHTML);
//     win.print();
//     win.close();
// }
/* ==============================
   COMBINED EXPORT (BOTTOM BUTTONS)
============================== */

/* ---- Combined CSV ---- */
function exportCSV() {
    let csv = "Chart,Dataset,Label,Value\n";

    Object.keys(chartMap).forEach(key => {
        const chart = chartMap[key];

        chart.data.datasets.forEach(ds => {
            chart.data.labels.forEach((label, i) => {
                csv += `${key},${ds.label || 'Value'},${label},${ds.data[i]}\n`;
            });
        });
    });

    downloadFile(csv, 'text/csv', 'all-charts.csv');
}

/* ---- Combined Excel ---- */
function exportExcel() {
    let html = `<table border="1">
        <tr>
            <th>Chart</th>
            <th>Dataset</th>
            <th>Label</th>
            <th>Value</th>
        </tr>`;

    Object.keys(chartMap).forEach(key => {
        const chart = chartMap[key];

        chart.data.datasets.forEach(ds => {
            chart.data.labels.forEach((label, i) => {
                html += `
                    <tr>
                        <td>${key}</td>
                        <td>${ds.label || 'Value'}</td>
                        <td>${label}</td>
                        <td>${ds.data[i]}</td>
                    </tr>`;
            });
        });
    });

    html += `</table>`;
    downloadFile(html, 'application/vnd.ms-excel', 'all-charts.xls');
}

/* ---- Combined PDF ---- */
function exportPDF() {
    const section = document.querySelector('.row.py-2');

    html2canvas(section, { scale: 2 }).then(canvas => {
        const pdf = new jspdf.jsPDF('l');
        pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 10, 10, 280, 160);
        pdf.save('all-charts.pdf');
    });
}

function printSingleChart(type) {
    const chart = chartMap[type];
    if (!chart) {
        alert('Chart not found');
        return;
    }

    const imgSrc = chart.canvas.toDataURL('image/png');

    // MUST be directly executed
    const win = window.open('', '_blank');

    if (!win) {
        alert('Popup blocked! Please allow popups.');
        return;
    }

    win.document.write(`
        <html>
        <head>
            <title>Chart Print</title>
            <style>
                body { text-align:center; padding:20px; }
                img { max-width:100%; }
            </style>
        </head>
        <body>
            <img src="${imgSrc}" onload="window.print();window.close();" />
        </body>
        </html>
    `);

    win.document.close();
}

function printSingleChart(type) {
    const chart = chartMap[type];
    if (!chart) return;

    const imgSrc = chart.canvas.toDataURL();

    const printArea = document.createElement('div');
    printArea.innerHTML = `<img src="${imgSrc}" style="width:100%">`;

    document.body.appendChild(printArea);
    window.print();
    document.body.removeChild(printArea);
}
/* ---- Combined Print ---- */
async function printCharts() {
    const section = document.querySelector('.row.py-2');
    const canvases = section.querySelectorAll('canvas');

    const clone = section.cloneNode(true);

    const cloneCanvases = clone.querySelectorAll('canvas');

    canvases.forEach((canvas, i) => {
        const img = document.createElement('img');
        img.src = canvas.toDataURL('image/png');
        img.style.maxWidth = '100%';

        cloneCanvases[i].replaceWith(img);
    });

    const win = window.open('', '', 'width=1200,height=800');
    win.document.open();
    win.document.write(`
        <html>
        <head>
            <title>Print Charts</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { padding: 20px; }
                .dropdown, button { display: none !important; }
            </style>
        </head>
        <body>
            ${clone.outerHTML}
        </body>
        </html>
    `); 
    win.document.close();

    win.onload = () => {
        win.focus();
        win.print();
        win.close();
    };
}

/* ==============================
   COMMON DOWNLOAD HELPER
============================== */
function downloadFile(content, type, filename) {
    const blob = new Blob([content], { type });
    const link = document.createElement('a');

    link.href = URL.createObjectURL(blob);
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

</script>

@endpush