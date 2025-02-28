@extends('layouts.app')

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Cover Photo Section -->
                <div class="relative">
                    <div class="h-64 w-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg">
                        <!-- Cover Photo -->
                    </div>
                    <div class="absolute -bottom-16 left-8">
                        <div class="h-32 w-32 rounded-full border-4 border-white bg-gray-200 flex items-center justify-center overflow-hidden">
                            @if($user->profile_photo_path)
                                <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Profile Info Section -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mt-12 pt-4">
                    <div class="mx-auto">
                        <div class="flex flex-col md:flex-row md:justify-between items-start mb-6">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h1>
                                <p class="text-gray-600">{{ $user->email }}</p>
                                @if($user->location)
                                    <p class="flex items-center mt-2 text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $user->location }}
                                    </p>
                                @endif
                            </div>
                            <div class="mt-4 md:mt-0 flex space-x-2">
                                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">Edit Profile</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Update Form -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password Form -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete User Form -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
