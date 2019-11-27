@extends('layouts.app')

@section('content')
    <expense_template :role_id="{{Auth::user()->roles}}"></expense_template>
@endsection