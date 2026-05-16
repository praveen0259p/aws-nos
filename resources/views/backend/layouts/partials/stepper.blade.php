@php 
$steps = [
    ['title' => 'Personal Info'],
    ['title' => 'Foreign University'],
    ['title' => 'Employment / Gap'],
    ['title' => 'Visa / Income'],
    ['title' => 'Upload Docs'],
    ['title' => 'Preview'],
];
@endphp

<div class="stepper">
    @foreach($steps as $index => $step)
        <div class="step {{ ($index + 1) == $activeSteps ? 'active' : (($index + 1) < $activeSteps ? 'completed' : '') }}">
            <span class="circle">{{ $index + 1 }}</span>
            <span>{{ $step['title'] }}</span>
        </div>
        @if($index < count($steps) - 1)
            <span class="connector"></span>
        @endif
    @endforeach
</div>


@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if(session('info'))
<div class="alert alert-info">
    {{ session('info') }}
</div>
@endif