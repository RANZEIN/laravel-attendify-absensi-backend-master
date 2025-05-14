<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QrAbsen;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class QrAbsenController extends Controller
{
    /**
     * Generate QR Code for check-in.
     */
    protected function generateQRCodeCheckin()
    {
        do {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code = substr(str_shuffle($characters), 0, 6); // Generate random 6-character code
        } while (QrAbsen::where('qr_checkin', $code)->exists()); // Ensure the code does not already exist

        return $code;
    }

    /**
     * Generate QR Code for checkout.
     */
    protected function generateQRCodeCheckout()
    {
        do {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code = substr(str_shuffle($characters), 0, 6); // Generate random 6-character code
        } while (QrAbsen::where('qr_checkout', $code)->exists()); // Ensure the code does not already exist

        return $code;
    }

    /**
     * Generate QR Code as a base64 encoded image.
     */
    private function generateQrCode($data)
    {
        $qrCode = QrCode::create($data)
            ->setSize(200)
            ->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return base64_encode($result->getString()); // Return base64 encoded string of the QR code
    }

    /**
     * Display a list of QR Absens for a specific month.
     */
    public function index(Request $request)
    {
        // Get the 'month' parameter from the request, or default to the current month
        $month = $request->get('month', Carbon::now()->format('Y-m')); // Default to current month

        // Query QR Absen data for the selected month
        $qrAbsen = QrAbsen::whereMonth('date', Carbon::createFromFormat('Y-m', $month)->month)
            ->whereYear('date', Carbon::createFromFormat('Y-m', $month)->year)
            ->paginate(10); // Paginate the results, showing 10 per page

        return view('pages.qr_absen.index', compact('qrAbsen', 'month')); // Pass data to the view
    }

    /**
     * Show the form to create QR Absens for a specific month.
     */
    public function create()
    {
        return view('pages.qr_absen.create'); // Show the form to create QR Absens
    }

    /**
     * Store QR Absens for a specific month.
     */
    public function store(Request $request)
    {
        // Validate the 'month' parameter to ensure it follows the 'Y-m' format
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        // Parse the provided month into a Carbon instance
        $month = Carbon::createFromFormat('Y-m', $request->month);
        $daysInMonth = $month->daysInMonth; // Get the number of days in the specified month

        // Loop through each day of the month and generate QR codes
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $month->copy()->setDay($day); // Set the date to the current day of the month

            // Create a new QR Absen record for the day
            QrAbsen::create([
                'date' => $date->format('Y-m-d'), // Store the date in 'Y-m-d' format
                'qr_checkin' => $this->generateQRCodeCheckin(), // Generate and store the check-in QR code
                'qr_checkout' => $this->generateQRCodeCheckout(), // Generate and store the checkout QR code
            ]);
        }

        // Redirect back with a success message
        return redirect()->route('qr_absens.index')->with('success', 'QR codes generated successfully for ' . $month->format('F Y'));
    }

    /**
     * Download the QR Absen data as a PDF.
     */
    public function downloadPDF($id)
    {
        // Find the QR Absen entry by its ID
        $qrAbsen = QrAbsen::findOrFail($id);

        // Generate the QR codes as base64 images
        $qrCodeCheckin = $this->generateQrCode($qrAbsen->qr_checkin);
        $qrCodeCheckout = $this->generateQrCode($qrAbsen->qr_checkout);

        // Prepare data to be passed to the PDF view
        $data = [
            'qrAbsen' => $qrAbsen,
            'qrCodeCheckin' => $qrCodeCheckin,
            'qrCodeCheckout' => $qrCodeCheckout,
        ];

        // Load the PDF view with the data and download the generated PDF
        $pdf = Pdf::loadView('pages.pdf.qr_absen', $data);

        return $pdf->download('qr_absen_' . $qrAbsen->date . '.pdf'); // Return the generated PDF as a download
    }
}
