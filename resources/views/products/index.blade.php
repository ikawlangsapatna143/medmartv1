<x-layout>
    <div class="w-full px-4 sm:px-6 lg:px-8 bg-white p-5 rounded-lg shadow-lg">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-5">
            <h1 class="text-2xl font-bold mb-2 sm:mb-0">Product List</h1>
            <p class="mb-2 sm:mb-0">@lang('message.total') Products: {{ $products->total() }}</p>
            <form method="GET" action="{{ route('products.index') }}" class="flex flex-col sm:flex-row w-full sm:w-auto">
                <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}"
                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-2 sm:mb-0">

                <select name="category"
                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent ml-2 mb-2 sm:mb-0">
                    <option value="">All Categories</option>
                    <option value="Medications" {{ request('category') == 'Medications' ? 'selected' : '' }}>Medications
                    </option>
                    <option value="Supplements" {{ request('category') == 'Supplements' ? 'selected' : '' }}>Supplements
                    </option>
                    <option value="Personal Care" {{ request('category') == 'Personal Care' ? 'selected' : '' }}>
                        Personal Care</option>
                    <option value="First Aid" {{ request('category') == 'First Aid' ? 'selected' : '' }}>First Aid
                    </option>
                    <option value="Medical Equipment"
                        {{ request('category') == 'Medical Equipment' ? 'selected' : '' }}>Medical Equipment</option>
                    <option value="Baby Products" {{ request('category') == 'Baby Products' ? 'selected' : '' }}>Baby
                        Products</option>
                    <option value="Health Devices" {{ request('category') == 'Health Devices' ? 'selected' : '' }}>
                        Health Devices</option>
                    <option value="Homeopathic Remedies"
                        {{ request('category') == 'Homeopathic Remedies' ? 'selected' : '' }}>Homeopathic Remedies
                    </option>
                    <option value="Herbal Products" {{ request('category') == 'Herbal Products' ? 'selected' : '' }}>
                        Herbal Products</option>
                    <option value="Skin Care" {{ request('category') == 'Skin Care' ? 'selected' : '' }}>Skin Care
                    </option>
                    <option value="Hair Care" {{ request('category') == 'Hair Care' ? 'selected' : '' }}>Hair Care
                    </option>
                    <option value="Oral Care" {{ request('category') == 'Oral Care' ? 'selected' : '' }}>Oral Care
                    </option>
                    <option value="Sexual Health" {{ request('category') == 'Sexual Health' ? 'selected' : '' }}>Sexual
                        Health</option>
                    <option value="Eye Care" {{ request('category') == 'Eye Care' ? 'selected' : '' }}>Eye Care
                    </option>
                    <option value="Ear Care" {{ request('category') == 'Ear Care' ? 'selected' : '' }}>Ear Care
                    </option>
                    <option value="Nutrition" {{ request('category') == 'Nutrition' ? 'selected' : '' }}>Nutrition
                    </option>
                    <option value="Wellness" {{ request('category') == 'Wellness' ? 'selected' : '' }}>Wellness
                    </option>
                </select>

                <button type="submit"
                    class="ml-2 px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Search</button>
            </form>
        </div>
        <div class="flex mb-5">
            <a href="{{ route('products.create') }}" class="btn text-lg w-full sm:w-auto text-center">@lang('message.add', ['item' => 'Product'])
            </a>
        </div>
        <div>
            @if (session('success'))
                <x-flashMsg msg="{{ session('success') }}" bg="bg-yellow-500" />
            @elseif (session('deleted'))
                <x-flashMsg msg="{{ session('deleted') }}" bg="bg-red-500" />
            @endif
        </div>

        <div class="relative overflow-x-auto sm:rounded-lg">
            @if ($products->isEmpty())
                <p class="text-center py-5 text-gray-500">Wow, this table is empty.</p>
            @else
                <table class="w-full text-left rtl:text-right">
                    <thead class="uppercase">
                        <tr>
                            <th scope="col" class="px-4 py-3 sm:px-6 hidden md:table-cell">ID</th>
                            <th scope="col" class="px-4 py-3 sm:px-6">Product Name</th>
                            <th scope="col" class="px-4 py-3 sm:px-6 hidden sm:table-cell">Generic</th>
                            <th scope="col" class="px-4 py-3 sm:px-6 hidden sm:table-cell">Category</th>
                            <th scope="col" class="px-4 py-3 sm:px-6 hidden sm:table-cell">Description</th>
                            <th scope="col" class="px-4 py-3 sm:px-6">Price</th>
                            <th scope="col" class="px-4 py-3 sm:px-6">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr class="hover:bg-green-300 cursor-pointer transition duration-150 ease-in-out"
                                onclick="window.location='{{ route('products.show', $product->id) }}'">
                                <td class="px-4 py-4 sm:px-6 hidden md:table-cell">{{ $product->id }}</td>
                                <td class="px-4 py-4 sm:px-6 font-medium text-gray-900 whitespace-nowrap">
                                    {{ Str::limit($product->product_name, 15) }}
                                </td>
                                <td class="px-4 py-4 sm:px-6 hidden sm:table-cell">
                                    {{ Str::limit($product->generic_name, 15) }}</td>
                                <td class="px-4 py-4 sm:px-6 hidden sm:table-cell">
                                    {{ Str::limit($product->category, 15) }}</td>
                                <td class="px-4 py-4 sm:px-6 hidden sm:table-cell">
                                    {{ Str::limit($product->product_description, 20) }}
                                </td>
                                <td class="px-4 py-4 sm:px-6 flex">
                                    ₱{{ number_format($product->price, 2) }}

                                    @if (!is_null($product->old_price))
                                        @if ($product->old_price < $product->price)
                                            <x-tooltip message="₱{{ number_format($product->old_price, 2) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-6 text-red-500 inline">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                                                </svg>
                                            </x-tooltip>
                                        @elseif($product->old_price > $product->price)
                                            <x-tooltip message="₱{{ number_format($product->old_price, 2) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-6 text-green-500 inline">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181" />
                                                </svg>
                                            </x-tooltip>
                                        @endif
                                    @endif

                                </td>



                                <td class="px-4 py-4 sm:px-6">
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                        <x-tooltip message="Click to view the full details of the product">
                                            <a href="{{ route('products.show', $product->id) }}"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                                onclick="event.stopPropagation();">View</a>
                                        </x-tooltip>
                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                            onclick="event.stopPropagation();">Edit</a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST"
                                            onsubmit="event.stopPropagation();">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="mt-4">
            {{ $products->appends(['search' => request('search')])->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</x-layout>
