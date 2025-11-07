@extends('layouts.pwa')

@section('title', 'Offline - Dental Practice PWA')
@section('description', 'You are currently offline')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <svg class="mx-auto h-24 w-24 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414"></path>
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-900 mb-4">You're Offline</h1>
        
        <p class="text-gray-600 mb-6">
            You are currently not connected to the internet. The app will continue working with cached data, and your changes will be synced automatically when you're back online.
        </p>
        
        <div class="space-y-4">
            <button 
                onclick="window.location.reload()" 
                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
                Try Again
            </button>
            
            <button 
                onclick="window.location.href='/pwa'" 
                class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors"
            >
                Go to App
            </button>
        </div>
        
        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                💡 Tip: Make sure you've visited the app at least once while online to enable offline functionality.
            </p>
        </div>
    </div>
</div>
@endsection