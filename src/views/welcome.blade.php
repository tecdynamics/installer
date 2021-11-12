@extends('vendor.installer.layouts.master')

@section('template_title')
      Welcome To Tec CMS Ecommerce App
@endsection

@section('title')
  Tec Ecommerce App
@endsection

@section('container')
    <p class="text-center">
      Welcome To Tec CMS Ecommerce App Installer
    </p>
    <p class="text-center">
      <a href="{{ route('LaravelInstaller::requirements') }}" class="button">
        Next
        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
      </a>
    </p>
@endsection
