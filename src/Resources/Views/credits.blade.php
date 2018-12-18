@extends('layouts.app')

@section('content')
    <credits-component :account="{{  $account }}"></credits-component>
@endsection
