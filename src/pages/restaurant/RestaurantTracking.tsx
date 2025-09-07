import React, { useState } from 'react';
import { MapPin, Clock, CheckCircle, Package, QrCode, Phone, User, Calendar } from 'lucide-react';

const RestaurantTracking: React.FC = () => {
  const [selectedDonation, setSelectedDonation] = useState<number | null>(1);

  const donations = [
    {
      id: 1,
      foodName: 'Fresh Sandwiches',
      quantity: '25 pieces',
      recipient: 'Hope Community Center',
      contactPerson: 'Sarah Johnson',
      phone: '+1 234-567-8900',
      pickupTime: '2024-01-15 14:00',
      status: 'in_transit',
      qrCode: 'FS-2024-001',
      trackingSteps: [
        { step: 'Match Approved', time: '2024-01-15 10:30', completed: true },
        { step: 'Pickup Scheduled', time: '2024-01-15 11:00', completed: true },
        { step: 'In Transit', time: '2024-01-15 13:45', completed: true },
        { step: 'Delivered', time: '', completed: false }
      ]
    },
    {
      id: 2,
      foodName: 'Pasta Salad',
      quantity: '3 containers',
      recipient: 'Family Support Services',
      contactPerson: 'Lisa Rodriguez',
      phone: '+1 234-567-8902',
      pickupTime: '2024-01-15 12:30',
      status: 'delivered',
      qrCode: 'PS-2024-002',
      trackingSteps: [
        { step: 'Match Approved', time: '2024-01-14 16:20', completed: true },
        { step: 'Pickup Scheduled', time: '2024-01-14 16:30', completed: true },
        { step: 'In Transit', time: '2024-01-15 12:30', completed: true },
        { step: 'Delivered', time: '2024-01-15 13:15', completed: true }
      ]
    },
    {
      id: 3,
      foodName: 'Bread Rolls',
      quantity: '50 pieces',
      recipient: 'Youth Program Center',
      contactPerson: 'Amanda Davis',
      phone: '+1 234-567-8904',
      pickupTime: '2024-01-15 16:00',
      status: 'scheduled',
      qrCode: 'BR-2024-003',
      trackingSteps: [
        { step: 'Match Approved', time: '2024-01-15 09:15', completed: true },
        { step: 'Pickup Scheduled', time: '2024-01-15 09:30', completed: true },
        { step: 'In Transit', time: '', completed: false },
        { step: 'Delivered', time: '', completed: false }
      ]
    }
  ];

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'scheduled': return 'bg-blue-100 text-blue-800';
      case 'in_transit': return 'bg-yellow-100 text-yellow-800';
      case 'delivered': return 'bg-green-100 text-green-800';
      case 'cancelled': return 'bg-red-100 text-red-800';
      default: return 'bg-gray-100 text-gray-800';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'scheduled': return 'Pickup Scheduled';
      case 'in_transit': return 'In Transit';
      case 'delivered': return 'Delivered';
      case 'cancelled': return 'Cancelled';
      default: return 'Unknown';
    }
  };

  const selectedDonationData = donations.find(d => d.id === selectedDonation);

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900">Donation Tracking</h1>
          <p className="text-gray-600 mt-1">Track the status of your food donations in real-time</p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Donations List */}
          <div className="lg:col-span-1">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Active Donations</h2>
            <div className="space-y-4">
              {donations.map((donation) => (
                <div
                  key={donation.id}
                  className={`bg-white rounded-lg shadow-lg p-4 cursor-pointer transition-all ${
                    selectedDonation === donation.id ? 'ring-2 ring-green-500' : 'hover:shadow-xl'
                  }`}
                  onClick={() => setSelectedDonation(donation.id)}
                >
                  <div className="flex justify-between items-start mb-2">
                    <h3 className="font-semibold text-gray-900">{donation.foodName}</h3>
                    <span className={`px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(donation.status)}`}>
                      {getStatusText(donation.status)}
                    </span>
                  </div>
                  
                  <div className="space-y-1 text-sm text-gray-600">
                    <div className="flex items-center">
                      <Package className="h-3 w-3 mr-2" />
                      {donation.quantity}
                    </div>
                    <div className="flex items-center">
                      <User className="h-3 w-3 mr-2" />
                      {donation.recipient}
                    </div>
                    <div className="flex items-center">
                      <Calendar className="h-3 w-3 mr-2" />
                      {donation.pickupTime}
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Tracking Details */}
          <div className="lg:col-span-2">
            {selectedDonationData ? (
              <div className="space-y-6">
                {/* Donation Info */}
                <div className="bg-white rounded-lg shadow-lg p-6">
                  <div className="flex justify-between items-start mb-4">
                    <h2 className="text-xl font-semibold text-gray-900">{selectedDonationData.foodName}</h2>
                    <span className={`px-3 py-1 rounded-full text-sm font-medium ${getStatusColor(selectedDonationData.status)}`}>
                      {getStatusText(selectedDonationData.status)}
                    </span>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div className="space-y-3">
                      <div className="flex items-center">
                        <Package className="h-5 w-5 text-gray-400 mr-3" />
                        <div>
                          <p className="text-sm text-gray-600">Quantity</p>
                          <p className="font-medium text-gray-900">{selectedDonationData.quantity}</p>
                        </div>
                      </div>
                      <div className="flex items-center">
                        <MapPin className="h-5 w-5 text-gray-400 mr-3" />
                        <div>
                          <p className="text-sm text-gray-600">Recipient</p>
                          <p className="font-medium text-gray-900">{selectedDonationData.recipient}</p>
                        </div>
                      </div>
                      <div className="flex items-center">
                        <User className="h-5 w-5 text-gray-400 mr-3" />
                        <div>
                          <p className="text-sm text-gray-600">Contact Person</p>
                          <p className="font-medium text-gray-900">{selectedDonationData.contactPerson}</p>
                        </div>
                      </div>
                    </div>

                    <div className="space-y-3">
                      <div className="flex items-center">
                        <Phone className="h-5 w-5 text-gray-400 mr-3" />
                        <div>
                          <p className="text-sm text-gray-600">Phone</p>
                          <p className="font-medium text-gray-900">{selectedDonationData.phone}</p>
                        </div>
                      </div>
                      <div className="flex items-center">
                        <Calendar className="h-5 w-5 text-gray-400 mr-3" />
                        <div>
                          <p className="text-sm text-gray-600">Pickup Time</p>
                          <p className="font-medium text-gray-900">{selectedDonationData.pickupTime}</p>
                        </div>
                      </div>
                      <div className="flex items-center">
                        <QrCode className="h-5 w-5 text-gray-400 mr-3" />
                        <div>
                          <p className="text-sm text-gray-600">QR Code</p>
                          <p className="font-medium text-gray-900">{selectedDonationData.qrCode}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                {/* Tracking Timeline */}
                <div className="bg-white rounded-lg shadow-lg p-6">
                  <h3 className="text-lg font-semibold text-gray-900 mb-6">Tracking Timeline</h3>
                  <div className="space-y-6">
                    {selectedDonationData.trackingSteps.map((step, index) => (
                      <div key={index} className="flex items-start">
                        <div className="flex-shrink-0">
                          {step.completed ? (
                            <div className="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                              <CheckCircle className="h-4 w-4 text-white" />
                            </div>
                          ) : (
                            <div className="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                              <div className="w-3 h-3 bg-white rounded-full"></div>
                            </div>
                          )}
                        </div>
                        <div className="ml-4 flex-1">
                          <div className="flex justify-between items-center">
                            <h4 className={`text-sm font-medium ${step.completed ? 'text-gray-900' : 'text-gray-500'}`}>
                              {step.step}
                            </h4>
                            {step.time && (
                              <span className="text-xs text-gray-500">{step.time}</span>
                            )}
                          </div>
                          {step.step === 'In Transit' && step.completed && (
                            <p className="text-xs text-gray-600 mt-1">
                              Pickup volunteer is on their way to the recipient location
                            </p>
                          )}
                          {step.step === 'Delivered' && step.completed && (
                            <p className="text-xs text-gray-600 mt-1">
                              Food delivered successfully. QR code verified.
                            </p>
                          )}
                        </div>
                        {index < selectedDonationData.trackingSteps.length - 1 && (
                          <div className="absolute left-4 mt-8 w-0.5 h-6 bg-gray-300"></div>
                        )}
                      </div>
                    ))}
                  </div>
                </div>

                {/* QR Code Section */}
                <div className="bg-white rounded-lg shadow-lg p-6">
                  <h3 className="text-lg font-semibold text-gray-900 mb-4">QR Code Verification</h3>
                  <div className="flex items-center justify-between">
                    <div>
                      <p className="text-sm text-gray-600 mb-2">
                        The recipient will scan this QR code to confirm pickup completion.
                      </p>
                      <p className="font-mono text-lg text-gray-900">{selectedDonationData.qrCode}</p>
                    </div>
                    <div className="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                      <QrCode className="h-12 w-12 text-gray-400" />
                    </div>
                  </div>
                </div>

                {/* Map Placeholder */}
                <div className="bg-white rounded-lg shadow-lg p-6">
                  <h3 className="text-lg font-semibold text-gray-900 mb-4">Live Location</h3>
                  <div className="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                    <div className="text-center">
                      <MapPin className="h-12 w-12 text-gray-400 mx-auto mb-2" />
                      <p className="text-gray-600">Interactive map would be displayed here</p>
                      <p className="text-sm text-gray-500">Showing real-time location of pickup volunteer</p>
                    </div>
                  </div>
                </div>
              </div>
            ) : (
              <div className="bg-white rounded-lg shadow-lg p-12 text-center">
                <Package className="h-12 w-12 text-gray-400 mx-auto mb-4" />
                <p className="text-gray-600">Select a donation to view tracking details</p>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default RestaurantTracking;