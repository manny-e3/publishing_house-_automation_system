@extends('layouts.admin')

@section('title', 'Prospect Enquiries')

@section('content')
 <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">Sales Overview</h3>
                                            <div class="nk-block-des text-soft">
                                                <p>Welcome to DashLite Dashboard Template.</p>
                                            </div>
                                        </div><!-- .nk-block-head-content -->
                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li>
                                                            <div class="dropdown">
                                                                <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-bs-toggle="dropdown"><em class="d-none d-sm-inline icon ni ni-calender-date"></em><span><span class="d-none d-md-inline">Last</span> 30 Days</span><em class="dd-indc icon ni ni-chevron-right"></em></a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        <li><a href="#"><span>Last 30 Days</span></a></li>
                                                                        <li><a href="#"><span>Last 6 Months</span></a></li>
                                                                        <li><a href="#"><span>Last 1 Years</span></a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div><!-- .nk-block-head-content -->
                                    </div><!-- .nk-block-between -->
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="row g-gs">
                                        <div class="col-xxl-6">
                                            <div class="row g-gs">
                                                <div class="col-lg-6 col-xxl-12">
                                                    <div class="card card-bordered">
                                                        <div class="card-inner">
                                                            <div class="card-title-group align-start mb-2">
                                                                <div class="card-title">
                                                                    <h6 class="title">Net Revenue</h6>
                                                                    <p>Total revenue collected from paid invoices.</p>
                                                                </div>
                                                                <div class="card-tools">
                                                                    <em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left" title="Sum of all successful payments"></em>
                                                                </div>
                                                            </div>
                                                            <div class="align-end gy-3 gx-5 flex-wrap flex-md-nowrap flex-lg-wrap flex-xxl-nowrap">
                                                                <div class="nk-sale-data-group flex-md-nowrap g-4">
                                                                    <div class="nk-sale-data">
                                                                        <span class="amount">₦{{ number_format($totalRevenue, 2) }}</span>
                                                                        <span class="sub-title">Lifetime Collection</span>
                                                                    </div>
                                                                    <div class="nk-sale-data">
                                                                        <span class="amount">₦{{ number_format($revenueThisMonth, 2) }} 
                                                                            @if($revenueChange != 0)
                                                                            <span class="change {{ $revenueChange >= 0 ? 'up text-success' : 'down text-danger' }}"><em class="icon ni ni-arrow-long-{{ $revenueChange >= 0 ? 'up' : 'down' }}"></em>{{ number_format(abs($revenueChange), 2) }}%</span>
                                                                            @endif
                                                                        </span>
                                                                        <span class="sub-title">This Month</span>
                                                                    </div>
                                                                </div>
                                                                <div class="nk-sales-ck sales-revenue">
                                                                    <canvas class="sales-bar-chart" id="salesRevenue"></canvas>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- .col -->
                                                <div class="col-lg-6 col-xxl-12">
                                                    <div class="row g-gs">
                                                        <div class="col-sm-6 col-lg-12 col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-inner">
                                                                    <div class="card-title-group align-start mb-2">
                                                                        <div class="card-title">
                                                                            <h6 class="title">Active Projects</h6>
                                                                        </div>
                                                                        <div class="card-tools">
                                                                            <em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left" title="Total manuscripts currently in production"></em>
                                                                        </div>
                                                                    </div>
                                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                                        <div class="nk-sale-data">
                                                                            <span class="amount">{{ $activeProjectsCount }}</span>
                                                                            <span class="sub-title">Current Pipeline</span>
                                                                        </div>
                                                                        <div class="nk-sales-ck">
                                                                            <canvas class="sales-bar-chart" id="activeSubscription"></canvas>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- .card -->
                                                        </div><!-- .col -->
                                                        <div class="col-sm-6 col-lg-12 col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-inner">
                                                                    <div class="card-title-group align-start mb-2">
                                                                        <div class="card-title">
                                                                            <h6 class="title">New Prospects</h6>
                                                                        </div>
                                                                        <div class="card-tools">
                                                                            <em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left" title="New enquiries received this month"></em>
                                                                        </div>
                                                                    </div>
                                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                                        <div class="nk-sale-data">
                                                                            <span class="amount">{{ $newProspectsCount }}</span>
                                                                            <span class="sub-title">This Month</span>
                                                                        </div>
                                                                        <div class="nk-sales-ck">
                                                                            <canvas class="sales-bar-chart" id="totalSubscription"></canvas>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- .card -->
                                                        </div><!-- .col -->
                                                    </div><!-- .row -->
                                                </div><!-- .col -->
                                            </div><!-- .row -->
                                        </div><!-- .col -->
                                        <div class="col-xxl-6">
                                            <div class="card card-bordered h-100">
                                                <div class="card-inner">
                                                    <div class="card-title-group align-start gx-3 mb-3">
                                                        <div class="card-title">
                                                            <h6 class="title">Project Stage Distribution</h6>
                                                            <p>Manuscripts categorized by their current operational stage.</p>
                                                        </div>
                                                    </div>
                                                    <div class="nk-sales-ck large pt-4" style="max-height: 250px;">
                                                        <canvas class="project-distribution-chart" id="projectDistribution"></canvas>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                        <div class="col-xxl-8">
                                            <div class="card card-bordered card-full">
                                                <div class="card-inner">
                                                    <div class="card-title-group">
                                                        <div class="card-title">
                                                            <h6 class="title"><span class="me-2">Recent Transactions</span></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-inner p-0 border-top">
                                                    <div class="nk-tb-list nk-tb-orders">
                                                        <div class="nk-tb-item nk-tb-head">
                                                            <div class="nk-tb-col"><span>Invoice No.</span></div>
                                                            <div class="nk-tb-col"><span>Author</span></div>
                                                            <div class="nk-tb-col tb-col-md"><span>Date</span></div>
                                                            <div class="nk-tb-col"><span>Amount</span></div>
                                                            <div class="nk-tb-col"><span>Status</span></div>
                                                        </div>
                                                        @forelse($recentTransactions as $transaction)
                                                        <div class="nk-tb-item">
                                                            <div class="nk-tb-col">
                                                                <span class="tb-lead"><a href="#">#{{ $transaction->invoice_number }}</a></span>
                                                            </div>
                                                            <div class="nk-tb-col">
                                                                <div class="user-card">
                                                                    <div class="user-name">
                                                                        <span class="tb-lead">{{ $transaction->prospect->name ?? 'Unknown' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="tb-sub">{{ $transaction->updated_at->format('d/m/Y') }}</span>
                                                            </div>
                                                            <div class="nk-tb-col">
                                                                <span class="tb-sub tb-amount">₦{{ number_format($transaction->total_paid, 2) }}</span>
                                                            </div>
                                                            <div class="nk-tb-col">
                                                                <span class="badge badge-dot badge-dot-xs bg-success">Paid</span>
                                                            </div>
                                                        </div>
                                                        @empty
                                                        <div class="nk-tb-item">
                                                            <div class="nk-tb-col text-center" colspan="5">No recent transactions.</div>
                                                        </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                        <div class="col-md-6 col-xxl-4">
                                            <div class="card card-bordered card-full">
                                                <div class="card-inner border-bottom">
                                                    <div class="card-title-group">
                                                        <div class="card-title">
                                                            <h6 class="title">Recent Activities</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="nk-activity">
                                                    @forelse($recentActivities as $activity)
                                                    <li class="nk-activity-item">
                                                        <div class="nk-activity-media user-avatar bg-primary-dim">
                                                            {{ strtoupper(substr($activity->user->name ?? 'S', 0, 2)) }}
                                                        </div>
                                                        <div class="nk-activity-data">
                                                            <div class="label">{{ $activity->action }} {{ class_basename($activity->model_type) }}</div>
                                                            <span class="time">{{ $activity->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </li>
                                                    @empty
                                                    <li class="nk-activity-item">
                                                        <div class="nk-activity-data">
                                                            <div class="label">No recent activities.</div>
                                                        </div>
                                                    </li>
                                                    @endforelse
                                                </ul>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                        <div class="col-md-6 col-xxl-4">
                                            <div class="card card-bordered card-full">
                                                <div class="card-inner-group">
                                                    <div class="card-inner">
                                                        <div class="card-title-group">
                                                            <div class="card-title">
                                                                <h6 class="title">New Users</h6>
                                                            </div>
                                                            <div class="card-tools">
                                                                <a href="html/user-list-regular.html" class="link">View All</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-inner card-inner-md">
                                                        <div class="user-card">
                                                            <div class="user-avatar bg-primary-dim">
                                                                <span>AB</span>
                                                            </div>
                                                            <div class="user-info">
                                                                <span class="lead-text">Abu Bin Ishtiyak</span>
                                                                <span class="sub-text">info@softnio.com</span>
                                                            </div>
                                                            <div class="user-action">
                                                                <div class="drodown">
                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger me-n1" data-bs-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            <li><a href="#"><em class="icon ni ni-setting"></em><span>Action Settings</span></a></li>
                                                                            <li><a href="#"><em class="icon ni ni-notify"></em><span>Push Notification</span></a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-inner card-inner-md">
                                                        <div class="user-card">
                                                            <div class="user-avatar bg-pink-dim">
                                                                <span>SW</span>
                                                            </div>
                                                            <div class="user-info">
                                                                <span class="lead-text">Sharon Walker</span>
                                                                <span class="sub-text">sharon-90@example.com</span>
                                                            </div>
                                                            <div class="user-action">
                                                                <div class="drodown">
                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger me-n1" data-bs-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            <li><a href="#"><em class="icon ni ni-setting"></em><span>Action Settings</span></a></li>
                                                                            <li><a href="#"><em class="icon ni ni-notify"></em><span>Push Notification</span></a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-inner card-inner-md">
                                                        <div class="user-card">
                                                            <div class="user-avatar bg-warning-dim">
                                                                <span>GO</span>
                                                            </div>
                                                            <div class="user-info">
                                                                <span class="lead-text">Gloria Oliver</span>
                                                                <span class="sub-text">gloria_72@example.com</span>
                                                            </div>
                                                            <div class="user-action">
                                                                <div class="drodown">
                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger me-n1" data-bs-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            <li><a href="#"><em class="icon ni ni-setting"></em><span>Action Settings</span></a></li>
                                                                            <li><a href="#"><em class="icon ni ni-notify"></em><span>Push Notification</span></a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-inner card-inner-md">
                                                        <div class="user-card">
                                                            <div class="user-avatar bg-success-dim">
                                                                <span>PS</span>
                                                            </div>
                                                            <div class="user-info">
                                                                <span class="lead-text">Phillip Sullivan</span>
                                                                <span class="sub-text">phillip-85@example.com</span>
                                                            </div>
                                                            <div class="user-action">
                                                                <div class="drodown">
                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger me-n1" data-bs-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            <li><a href="#"><em class="icon ni ni-setting"></em><span>Action Settings</span></a></li>
                                                                            <li><a href="#"><em class="icon ni ni-notify"></em><span>Push Notification</span></a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                        <div class="col-lg-6 col-xxl-4">
                                            <div class="card card-bordered h-100">
                                                <div class="card-inner border-bottom">
                                                    <div class="card-title-group">
                                                        <div class="card-title">
                                                            <h6 class="title">Support Requests</h6>
                                                        </div>
                                                        <div class="card-tools">
                                                            <a href="#" class="link">All Tickets</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="nk-support">
                                                    <li class="nk-support-item">
                                                        <div class="user-avatar">
                                                            <img src="./images/avatar/a-sm.jpg" alt="">
                                                        </div>
                                                        <div class="nk-support-content">
                                                            <div class="title">
                                                                <span>Vincent Lopez</span><span class="badge badge-dot badge-dot-xs bg-warning ms-1">Pending</span>
                                                            </div>
                                                            <p>Thanks for contact us with your issues...</p>
                                                            <span class="time">6 min ago</span>
                                                        </div>
                                                    </li>
                                                    <li class="nk-support-item">
                                                        <div class="user-avatar bg-purple-dim">
                                                            <span>DM</span>
                                                        </div>
                                                        <div class="nk-support-content">
                                                            <div class="title">
                                                                <span>Daniel Moore</span><span class="badge badge-dot badge-dot-xs bg-info ms-1">Open</span>
                                                            </div>
                                                            <p>Thanks for contact us with your issues...</p>
                                                            <span class="time">2 Hours ago</span>
                                                        </div>
                                                    </li>
                                                    <li class="nk-support-item">
                                                        <div class="user-avatar">
                                                            <img src="./images/avatar/b-sm.jpg" alt="">
                                                        </div>
                                                        <div class="nk-support-content">
                                                            <div class="title">
                                                                <span>Larry Henry</span><span class="badge badge-dot badge-dot-xs bg-success ms-1">Solved</span>
                                                            </div>
                                                            <p>Thanks for contact us with your issues...</p>
                                                            <span class="time">3 Hours ago</span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                        <div class="col-lg-6 col-xxl-4">
                                            <div class="card card-bordered h-100">
                                                <div class="card-inner border-bottom">
                                                    <div class="card-title-group">
                                                        <div class="card-title">
                                                            <h6 class="title">Notifications</h6>
                                                        </div>
                                                        <div class="card-tools">
                                                            <a href="#" class="link">View All</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-inner">
                                                    <div class="timeline">
                                                        <h6 class="timeline-head">November, 2019</h6>
                                                        <ul class="timeline-list">
                                                            <li class="timeline-item">
                                                                <div class="timeline-status bg-primary is-outline"></div>
                                                                <div class="timeline-date">13 Nov <em class="icon ni ni-alarm-alt"></em></div>
                                                                <div class="timeline-data">
                                                                    <h6 class="timeline-title">Submited KYC Application</h6>
                                                                    <div class="timeline-des">
                                                                        <p>Re-submitted KYC Application form.</p>
                                                                        <span class="time">09:30am</span>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="timeline-item">
                                                                <div class="timeline-status bg-primary"></div>
                                                                <div class="timeline-date">13 Nov <em class="icon ni ni-alarm-alt"></em></div>
                                                                <div class="timeline-data">
                                                                    <h6 class="timeline-title">Submited KYC Application</h6>
                                                                    <div class="timeline-des">
                                                                        <p>Re-submitted KYC Application form.</p>
                                                                        <span class="time">09:30am</span>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="timeline-item">
                                                                <div class="timeline-status bg-pink"></div>
                                                                <div class="timeline-date">13 Nov <em class="icon ni ni-alarm-alt"></em></div>
                                                                <div class="timeline-data">
                                                                    <h6 class="timeline-title">Submited KYC Application</h6>
                                                                    <div class="timeline-des">
                                                                        <p>Re-submitted KYC Application form.</p>
                                                                        <span class="time">09:30am</span>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                    </div><!-- .row -->
                                </div><!-- .nk-block -->
                            </div>
                        </div>
                    </div>
                </div>
@endsection

@push('scripts')
<script>
    (function (NioApp, $) {
        'use strict';

        var projectDistributionData = {
            labels: @json($stageLabels),
            dataUnit: 'Projects',
            datasets: [{
                label: "Projects",
                color: "#9d72ff",
                background: NioApp.hexRGB('#9d72ff', .2),
                data: @json($stageCounts)
            }]
        };

        function projectDistributionChart(selector, set_data) {
            var $selector = $(selector || '.project-distribution-chart');
            $selector.each(function () {
                var $self = $(this),
                    _self_id = $self.attr('id'),
                    _get_data = projectDistributionData,
                    _canvas = document.getElementById(_self_id).getContext("2d");

                var chart_data = [];
                for (var i = 0; i < _get_data.datasets.length; i++) {
                    chart_data.push({
                        label: _get_data.datasets[i].label,
                        data: _get_data.datasets[i].data,
                        backgroundColor: _get_data.datasets[i].background,
                        borderWidth: 2,
                        borderColor: _get_data.datasets[i].color,
                        hoverBackgroundColor: _get_data.datasets[i].background,
                        hoverBorderColor: _get_data.datasets[i].color,
                    });
                }
                var chart = new Chart(_canvas, {
                    type: 'bar',
                    data: {
                        labels: _get_data.labels,
                        datasets: chart_data,
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        maintainAspectRatio: false,
                        tooltips: {
                            enabled: true,
                            backgroundColor: '#eff6ff',
                            titleFontSize: 13,
                            titleFontColor: '#6783b8',
                            titleMarginBottom: 6,
                            bodyFontColor: '#9eaecf',
                            bodyFontSize: 12,
                            bodySpacing: 4,
                            yPadding: 10,
                            xPadding: 10,
                            displayColors: false
                        },
                        scales: {
                            yAxes: [{
                                display: true,
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 12,
                                    fontColor: '#9eaecf',
                                    padding: 10,
                                    stepSize: 5
                                },
                                gridLines: {
                                    color: NioApp.hexRGB('#526484', .2),
                                    tickMarkLength: 0,
                                    zeroLineColor: NioApp.hexRGB('#526484', .2)
                                },
                            }],
                            xAxes: [{
                                display: true,
                                ticks: {
                                    fontSize: 12,
                                    fontColor: '#9eaecf',
                                    padding: 5
                                },
                                gridLines: {
                                    color: "transparent",
                                    tickMarkLength: 10,
                                    zeroLineColor: 'transparent',
                                }
                            }]
                        }
                    }
                });
            });
        }

        NioApp.comms.docReady.push(function () {
            projectDistributionChart();
        });

    })(NioApp, jQuery);
</script>
@endpush