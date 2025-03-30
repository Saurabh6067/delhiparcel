@php
    $sr = 1;
    // Define the service type mapping
    $serviceTypes = [
        'stex' => 'Express',
        'stss' => 'Standard',
        'stse' => 'Super Express',
    ];
    foreach ($serviceData as $service) {
        $servicesId = explode(',', $service->servicesId);
        foreach ($servicesId as $id) {
            $serviceDetail = \App\Models\Service::find($id);
            $title = $serviceDetail ? $serviceDetail->title : 'N/A';
            $price = $serviceDetail ? $serviceDetail->price : 0.0;
            $services = $serviceDetail ? $serviceDetail->type : '';
            // Use the mapping array to set the service name
            if (array_key_exists($services, $serviceTypes)) {
                $services = $serviceTypes[$services];
            }
            echo '<tr>
                    <td>' . $sr++ . '</td>
                    <td>' . $services . '</td>
                    <td>' . $title . '</td>
                    <td>' . number_format($price, 2) . '</td>
                  </tr>';
        }
    }
@endphp
