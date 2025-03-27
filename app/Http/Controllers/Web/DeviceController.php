<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ButtonStateResource;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    // Fetch all buttons from the database and return them as JSON
    public function getButtons()
    {
        $buttons = Device::all(); // Fetch all buttons from the button_states table

        return response()->json([
            'buttons' => $buttons,  // Return buttons as JSON
        ]);
    }

    // Toggle the button state (on/off) and update in the database
    public function toggle(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'id' => 'required|exists:devices,id',  // Ensure the button ID exists in the database
            'device_state' => 'required',
        ]);

        $button = Device::findOrFail($validated['id']);
        $button->device_state = $validated['device_state']; // Update the button state
        $button->save(); // Save the updated button state to the database

        // Return a response confirming the toggle was successful
        return response()->json([
            'message' => 'Button state updated successfully.',
            'device_state' => $button->device_state,  // Return the updated button state
        ]);
    }
}
