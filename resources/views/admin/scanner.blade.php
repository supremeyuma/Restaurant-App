<x-layouts.admin>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">QR Order Scanner</h1>

        <div id="scanner" class="w-full mb-4"></div>

        <div id="result" class="bg-white p-4 rounded shadow hidden">
            <h2 class="font-semibold mb-2">Order Found</h2>
            <div id="order-details"></div>

            <form method="POST" action="{{ route('admin.scan.complete') }}">
                @csrf
                <input type="hidden" name="order_id" id="order_id">
                <button type="submit" class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Mark as Completed
                </button>
            </form>
        </div>

        <div id="error" class="text-red-600 font-semibold hidden">Order not found</div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        const scanner = new Html5Qrcode("scanner");

        function onScanSuccess(decodedText) {
            fetch(`/admin/scan/result/${decodedText}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('error').classList.remove('hidden');
                        document.getElementById('result').classList.add('hidden');
                    } else {
                        document.getElementById('error').classList.add('hidden');
                        document.getElementById('result').classList.remove('hidden');

                        document.getElementById('order_id').value = data.id;

                        let html = `<p><strong>Name:</strong> ${data.name ?? '-'}</p>
                                    <p><strong>Phone:</strong> ${data.phone ?? '-'}</p>
                                    <p><strong>Status:</strong> ${data.status}</p>
                                    <p><strong>Pickup Code:</strong> ${data.pickup_code}</p>
                                    <p><strong>Total:</strong> ₦${data.amount}</p>
                                    <h3 class="font-semibold mt-2">Items:</h3>
                                    <ul class="list-disc pl-5">`;

                        data.items.forEach(item => {
                            html += `<li>${item.item.name} x${item.quantity}</li>`;
                        });

                        html += `</ul>`;

                        document.getElementById('order-details').innerHTML = html;
                    }

                    scanner.stop();
                });
        }

        Html5Qrcode.getCameras().then(devices => {
            if (devices.length) {
                scanner.start(devices[0].id, {
                    fps: 10,
                    qrbox: 250
                }, onScanSuccess);
            }
        });
    </script>
</x-layouts.admin>
