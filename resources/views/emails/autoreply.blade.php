@component('mail::layout')
{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        {{ config('app.name') }}
    @endcomponent
@endslot

{{-- Body --}}
{{__('Hi')}} {{ $recieverName ?? ''}},
{{-- Custom Message --}}
<p>{!! $message !!}</p>
{{-- Submission Number --}}
@if(isset($submissionNumber) && $formConfig['include_submission_number'])
<p>{{__('Your request has been received')}}: <strong>{{ $submissionNumber }}</strong></p>
@endif

{{-- Form Data --}}
@if(!empty($formData) && $formConfig['include_body'])
<p>{{__('Your email details')}}:</p>
<table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
    <thead>
        <tr>
            <th style="border: 1px solid #ddd; padding: 8px;">{{__('Field')}}</th>
            <th style="border: 1px solid #ddd; padding: 8px;">{{__('Value')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($formData as $key => $value)
        <tr>
            <td style="border: 1px solid #ddd; padding: 8px;"><strong>{!! $key !!}</strong></td>
            <td style="border: 1px solid #ddd; padding: 8px;">
                @if(is_array($value))
                    {!! implode(', ', $value) !!}
                @else
                    {!! $value !!}
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Subcopy --}}
@isset($subcopy)
    @slot('subcopy')
        @component('mail::subcopy')
            {{ $subcopy }}
        @endcomponent
    @endslot
@endisset

{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        &copy; {{ date('Y') }} {{ config('app.name') }}. {{__('All rights reserved')}}.
    @endcomponent
@endslot
@endcomponent