@extends('layouts.app')

@section('content')
<div class="mx-5">
 <h1 class="mb-4">Expired Notes <span class="text-success">(Opened)</span> </h1>

 @if ($notifications->isNotEmpty())
 @foreach ($notifications as $notification)
     <div class="card border-bottom border-info shadow-lg mb-4 bg-body-tertiary">
         <div class="card-header bg-info">
             Note
         </div>
         <div class="card-body">
             {{-- <img src="" alt=""> --}}
             <blockquote class="blockquote shadow mb-0">
                 <p><span>Title: </span>{{ $notification->data['note_title'] }}</p>
                 <footer class="blockquote-footer">{{ $notification->data['sender'] }} <cite
                         title="Source Title"></cite></footer>
             </blockquote>
         </div>
         
     </div>
 @endforeach
@else
 <div class="alert alert-info text-center">There is no opened notes yet!!</div>
@endif
 </div>
@endsection