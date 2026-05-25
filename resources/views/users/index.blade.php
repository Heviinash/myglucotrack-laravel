@extends('layouts.app')

@section('page-title', 'Tenant List')

@section('content')

    <div class="max-w-7xl mx-auto py-6">

        <div class="bg-white shadow rounded-xl overflow-hidden">

            <table class="w-full">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left">Name</th>
                        <th class="p-4 text-left">Email</th>
                        <th class="p-4 text-left">Role</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($users as $user)

                        <tr class="border-t">

                            <td class="p-4">
                                {{ $user->fullname }}
                            </td>

                            <td class="p-4">
                                {{ $user->email }}
                            </td>

                            <td class="p-4">
                                {{ $user->role }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

@endsection
