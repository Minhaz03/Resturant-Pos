
    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-white border-y border-slate-100 min-h-[calc(100vh-80px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-brand-red font-bold text-sm tracking-wider uppercase">Simple Pricing</span>
                <h2 class="font-outfit font-extrabold text-3xl sm:text-4xl text-brand-navy">Choose Your Plan</h2>
                <p class="text-slate-500">Transparent pricing. No hidden fees. Select the plan that fits your restaurant.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                @forelse($plans as $plan)
                    <div
                        class="bg-white border border-slate-200 rounded-3xl p-8 hover:border-brand-red/30 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col relative overflow-hidden">
                        @if ($plan->name == 'Pro' || $plan->name == 'Premium')
                            <div
                                class="absolute top-0 right-0 bg-gradient-to-r from-brand-gold to-yellow-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl uppercase tracking-wider">
                                Most Popular
                            </div>
                        @endif
                        <h3 class="font-outfit font-bold text-xl text-brand-navy mb-2">{{ $plan->name }}</h3>
                        <div class="text-slate-500 text-sm mb-6 min-h-[40px]">{{ $plan->description }}</div>

                        <div class="mb-8">
                            <span class="text-4xl font-extrabold text-brand-navy">৳{{ number_format($plan->price) }}</span>
                            <span class="text-slate-500 font-medium">/{{ $plan->billing_cycle }}</span>
                        </div>

                        <ul class="space-y-4 mb-8 flex-1">
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-brand-gold mt-1"></i>
                                <span class="text-slate-600 text-sm">Full POS Access</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-brand-gold mt-1"></i>
                                <span class="text-slate-600 text-sm">Kitchen Display System</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-brand-gold mt-1"></i>
                                <span class="text-slate-600 text-sm">Inventory Management</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-brand-gold mt-1"></i>
                                <span class="text-slate-600 text-sm">Unlimited Staff Accounts</span>
                            </li>
                        </ul>

                        <a href="{{ url('/#register') }}" onclick="if(document.getElementById('plan_id')) document.getElementById('plan_id').value = '{{ $plan->id }}';"
                            class="w-full block text-center bg-brand-red hover:bg-brand-redDark text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-red-900/10 hover:shadow-red-900/20 transition-all duration-200">
                            Get Started
                        </a>
                    </div>
                @empty
                    <div
                        class="col-span-3 text-center text-slate-500 py-10 border border-dashed border-slate-300 rounded-3xl">
                        <i class="bi bi-emoji-frown text-3xl mb-3 block text-slate-400"></i>
                        Pricing plans will be available soon. Please contact sales.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

