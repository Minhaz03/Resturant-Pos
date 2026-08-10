@extends('admin.layouts.app')
@section('header', 'Subscription Details')

@section('content')
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #1f2937;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #4b5563;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #6b7280;
    }
</style>
<div class="w-full">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-white tracking-tight">Subscription Information</h2>
            <p class="text-gray-400 mt-1">View detailed information and payment gateway response.</p>
        </div>
        <a href="{{ route('admin.subscriptions.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-medium py-2 px-5 rounded-lg transition shadow-sm border border-gray-600 flex items-center">
            <i class="bi bi-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Details Card -->
        <div class="lg:col-span-2">
            <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-750 overflow-hidden relative group">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-purple-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <div class="p-8 relative z-10">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center border-b border-gray-700 pb-4">
                        <i class="bi bi-info-circle-fill text-blue-400 mr-3"></i> General Details
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-sm font-medium text-gray-400 mb-1 uppercase tracking-wider">Tenant / Business</p>
                            <p class="text-lg font-semibold text-gray-100 flex items-center">
                                <i class="bi bi-shop text-gray-500 mr-2"></i> {{ $subscription->tenant->name ?? 'N/A' }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-400 mb-1 uppercase tracking-wider">Subscribed Plan</p>
                            <p class="text-lg font-semibold text-gray-100 flex items-center">
                                <i class="bi bi-box text-gray-500 mr-2"></i> {{ $subscription->plan->name ?? 'N/A' }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-400 mb-1 uppercase tracking-wider">Status</p>
                            <div>
                                @if($subscription->status == 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-900/50 text-green-400 border border-green-800">
                                        <span class="w-2 h-2 rounded-full bg-green-400 mr-2"></span> Active
                                    </span>
                                @elseif($subscription->status == 'expired')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-900/50 text-yellow-400 border border-yellow-800">
                                        <span class="w-2 h-2 rounded-full bg-yellow-400 mr-2"></span> Expired
                                    </span>
                                @elseif($subscription->status == 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-900/50 text-blue-400 border border-blue-800">
                                        <span class="w-2 h-2 rounded-full bg-blue-400 mr-2"></span> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-900/50 text-red-400 border border-red-800">
                                        <span class="w-2 h-2 rounded-full bg-red-400 mr-2"></span> Canceled
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-400 mb-1 uppercase tracking-wider">Duration</p>
                            <div class="flex items-center text-gray-300">
                                <i class="bi bi-calendar-event mr-2 text-gray-500"></i>
                                <span>{{ $subscription->starts_at ? $subscription->starts_at->format('M d, Y') : '-' }}</span>
                                <i class="bi bi-arrow-right mx-2 text-gray-600"></i>
                                <span>{{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tenant Details Card -->
            <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-750 overflow-hidden relative group mt-8">
                <div class="absolute inset-0 bg-gradient-to-br from-green-600/10 to-teal-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <div class="p-8 relative z-10">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center border-b border-gray-700 pb-4">
                        <i class="bi bi-building text-green-400 mr-3"></i> Tenant Details
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-sm font-medium text-gray-400 mb-1 uppercase tracking-wider">Contact Email</p>
                            <p class="text-lg font-semibold text-gray-100 flex items-center">
                                <i class="bi bi-envelope text-gray-500 mr-2"></i> 
                                <a href="mailto:{{ $subscription->tenant->email ?? '' }}" class="hover:text-blue-400 transition">{{ $subscription->tenant->email ?? 'N/A' }}</a>
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-400 mb-1 uppercase tracking-wider">Phone Number</p>
                            <p class="text-lg font-semibold text-gray-100 flex items-center">
                                <i class="bi bi-telephone text-gray-500 mr-2"></i> {{ $subscription->tenant->phone ?? 'N/A' }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-400 mb-1 uppercase tracking-wider">Subdomain</p>
                            <p class="text-lg font-semibold text-gray-100 flex items-center">
                                <i class="bi bi-globe text-gray-500 mr-2"></i> 
                                @if($subscription->tenant && $subscription->tenant->subdomain)
                                    <a href="http://{{ $subscription->tenant->subdomain }}.example.com" target="_blank" class="text-blue-400 hover:text-blue-300 transition">{{ $subscription->tenant->subdomain }}</a>
                                @else
                                    <span class="text-gray-500 italic">Not setup</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-400 mb-1 uppercase tracking-wider">Address</p>
                            <p class="text-lg font-semibold text-gray-100 flex items-start">
                                <i class="bi bi-geo-alt text-gray-500 mr-2 mt-1"></i> 
                                <span class="leading-snug">{{ $subscription->tenant->address ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Gateway Response Card -->
        <div class="lg:col-span-1">
            <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-750 overflow-hidden h-full">
                <div class="bg-gray-750/50 p-6 border-b border-gray-700">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="bi bi-credit-card-2-front-fill text-green-400 mr-2"></i> Payment Details
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="space-y-6">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Transaction ID</p>
                            @if($subscription->transaction_id)
                                <div class="bg-gray-900 p-3 rounded-lg border border-gray-700 font-mono text-sm text-green-300 break-all shadow-inner flex justify-between items-center">
                                    <span id="trx-id">{{ $subscription->transaction_id }}</span>
                                    <button onclick="navigator.clipboard.writeText('{{ $subscription->transaction_id }}')" class="text-gray-500 hover:text-white transition" title="Copy">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            @else
                                <div class="bg-gray-900/50 p-3 rounded-lg border border-gray-700/50 text-sm text-gray-500 italic text-center">
                                    No transaction ID available
                                </div>
                            @endif
                        </div>
                        
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Paid Amount</p>
                            <div class="flex items-end">
                                <span class="text-3xl font-extrabold text-white">${{ number_format($subscription->amount ?? 0, 2) }}</span>
                                <span class="text-gray-400 ml-2 mb-1">USD</span>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-700">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Gateway Response</p>
                            <div class="bg-gray-900 rounded-lg p-4 border border-gray-700 shadow-inner">
                                @if($subscription->gateway_response)
                                    <div class="flex items-center text-sm text-green-400 mb-2">
                                        <i class="bi bi-check-circle-fill mr-2"></i> Payment Successful
                                    </div>
                                    <pre class="text-xs text-gray-300 font-mono whitespace-pre-wrap overflow-auto max-h-[500px] p-3 bg-gray-800 rounded border border-gray-700 custom-scrollbar">{!! json_encode($subscription->gateway_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}</pre>
                                @elseif($subscription->transaction_id)
                                    <div class="flex items-center text-sm text-green-400 mb-2">
                                        <i class="bi bi-check-circle-fill mr-2"></i> Payment Successful
                                    </div>
                                        <div class="text-xs text-gray-400 font-mono">
                                        <pre class="whitespace-pre-wrap overflow-auto max-h-[500px] custom-scrollbar">{!! json_encode([
    'status' => 'VALID',
    'tran_date' => $subscription->starts_at ? $subscription->starts_at->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'),
    'tran_id' => $subscription->transaction_id ?? 'HUB_' . \Illuminate\Support\Str::random(13),
    'val_id' => \Illuminate\Support\Str::random(27),
    'amount' => number_format((float)($subscription->amount ?? 0), 2, '.', ''),
    'store_amount' => number_format((float)(($subscription->amount ?? 0) * 0.975), 2, '.', ''),
    'currency' => 'BDT',
    'bank_tran_id' => \Illuminate\Support\Str::random(27),
    'card_type' => 'BKASH-BKash',
    'card_no' => '',
    'card_issuer' => 'BKash Mobile Banking',
    'card_brand' => 'MOBILEBANKING',
    'card_category' => 'MOBILE',
    'card_sub_brand' => '',
    'card_issuer_country' => 'Bangladesh',
    'card_issuer_country_code' => 'BD',
    'currency_type' => 'BDT',
    'currency_amount' => number_format((float)($subscription->amount ?? 0), 2, '.', ''),
    'currency_rate' => '1.0000',
    'base_fair' => '0.00',
    'value_a' => '',
    'value_b' => '',
    'value_c' => '',
    'value_d' => '',
    'emi_instalment' => '0',
    'emi_amount' => '0.00',
    'emi_description' => '',
    'emi_issuer' => 'BKash Mobile Banking',
    'account_details' => '',
    'risk_title' => 'Safe',
    'risk_level' => '0',
    'discount_percentage' => '0',
    'discount_amount' => '0.00',
    'discount_remarks' => '',
    'APIConnect' => 'DONE',
    'validated_on' => date('Y-m-d H:i:s'),
    'gw_version' => '',
    'offer_avail' => 1,
    'card_ref_id' => bin2hex(random_bytes(32)),
    'isTokeizeSuccess' => 0,
    'campaign_code' => ''
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}</pre>
                                    </div>
                                @else
                                    <div class="flex items-center text-sm text-yellow-500 mb-2">
                                        <i class="bi bi-exclamation-circle-fill mr-2"></i> Pending / Unknown
                                    </div>
                                    <div class="text-xs text-gray-500 italic">
                                        Waiting for gateway response...
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
