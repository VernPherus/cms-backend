@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <a href="{{ route('disbursements.index') }}" class="text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back to Dashboard</a>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Disbursement Details</h1>
        <div>
            @if($disbursement->status == 'pending')
                <form action="{{ route('disbursements.approve', $disbursement->id) }}" method="POST" class="inline">
                    @csrf @method('PATCH')
                    <button class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700">
                        ✓ Approve Disbursement
                    </button>
                </form>
            @else
                <span class="bg-green-100 text-green-800 text-lg px-4 py-1 rounded font-bold border border-green-200">
                    STATUS: APPROVED
                </span>
            @endif
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        
        <div class="bg-gray-50 p-6 border-b border-gray-200 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">Payee</p>
                <p class="text-lg font-semibold">{{ $disbursement->payee->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">Fund Source</p>
                <p class="text-gray-800">{{ $disbursement->fundSource->code }}</p>
                <p class="text-xs text-gray-500">{{ $disbursement->fundSource->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">Check Number</p>
                <p class="text-gray-800 font-mono">{{ $disbursement->check_number ?? '---' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">Date Entered</p>
                <p class="text-gray-800">{{ $disbursement->date_entered->format('M d, Y') }}</p>
            </div>
        </div>

        <div class="p-6">
            <div class="mb-8">
                <p class="text-xs text-gray-500 uppercase font-bold mb-1">Purpose / Particulars</p>
                <p class="text-gray-800 bg-gray-50 p-3 rounded border">{{ $disbursement->purpose }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div>
                    <h3 class="font-bold text-gray-700 border-b pb-2 mb-3">Disbursement Items</h3>
                    <table class="w-full text-sm">
                        <tbody class="divide-y">
                            @foreach($disbursement->items as $item)
                            <tr>
                                <td class="py-2 text-gray-600">{{ $item->description }}</td>
                                <td class="py-2 text-right font-medium">{{ number_format($item->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t-2 border-gray-300">
                            <tr>
                                <td class="py-3 font-bold text-gray-800">GROSS AMOUNT</td>
                                <td class="py-3 text-right font-bold text-gray-800">{{ number_format($disbursement->gross_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div>
                    <h3 class="font-bold text-red-700 border-b pb-2 mb-3">Deductions</h3>
                    @if($disbursement->deductions->count() > 0)
                        <table class="w-full text-sm">
                            <tbody class="divide-y">
                                @foreach($disbursement->deductions as $deduction)
                                <tr>
                                    <td class="py-2 text-gray-600">{{ $deduction->deduction_type }}</td>
                                    <td class="py-2 text-right font-medium text-red-600">({{ number_format($deduction->amount, 2) }})</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-t-2 border-gray-300">
                                <tr>
                                    <td class="py-3 font-bold text-gray-600">Total Deductions</td>
                                    <td class="py-3 text-right font-bold text-red-600">({{ number_format($disbursement->total_deductions, 2) }})</td>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <p class="text-gray-400 italic text-sm mt-4">No deductions recorded.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-gray-800 text-white p-6 flex justify-between items-center">
            <span class="text-xl font-light">NET AMOUNT PAYABLE</span>
            <span class="text-3xl font-bold">{{ number_format($disbursement->net_amount, 2) }}</span>
        </div>
    </div>
</div>
@endsection