import React, { useState } from 'react';
import { MapPin, Clock, CheckCircle, Package, QrCode, Phone, User, Calendar, Filter, AlertTriangle } from 'lucide-react';

const AdminTracking: React.FC = () => {
  const [filterStatus, setFilterStatus] = useState('all');
  const [filterRegion, setFilterRegion] = useState('all');

  const systemDonations = [
    {
      id: 1,
      restaurant: 'Golden Spoon',
      foodName: 'Fresh Sandwiches',
      quantity: '25 pieces',
      recipient: 'Hope Community Center',
      contactPerson: 'Sarah Johnson',
      phone: '+1 234-567-8900',
      region: 'Central',
      pickupTime: '2024-01-15 14:00',
      status: 'in_transit',
      qrCode: 'FS-2024-001',
      priority: 'high',
      trackingSteps: [
        { step: 'Match Approved', time: '2024-01-15 10:30', completed: true },
        { step: 'Pickup Scheduled', time: '2024-01-15 11:00', completed: true },
        { step: 'In Transit', time: '2024-01-15 13:45', completed: true },
        { step: 'Delivered', time: '', completed: false }
      ]
    },
    {
      id: 2,
      restaurant: 'Pizza Corner',
      foodName: 'Pasta Salad',
      quantity: '3 containers',
      recipient: 'Family Support Services',
      contactPerson: 'Lisa Rodriguez',
      phone: '+1 234-567-8902',
      region: 'North',
      pickupTime: '2024-01-15 12:30',
      status: 'delivered',
      qrCode: 'PS-2024-002',
      priority: 'medium',
      trackingSteps: [
        { step: 'Match Approved', time: '2024-01-14 16:20', completed: true },
        { step: 'Pickup Scheduled', time: '2024-01-14 16:30', completed: true },
        { step: 'In Transit', time: '2024-01-15 12:30', completed: true },
        { step: 'Delivered', time: '2024-01-15 13:15', completed: true }
      ]
    },
    {
      id: 3,
      restaurant: 'Bread Basket',
      foodName: 'Bread Rolls',
      quantity: '50 pieces',
      recipient: 'Youth Program Center',
      contactPerson: 'Amanda Davis',
      phone: '+1 234-567-8904',
      region: 'South',
      pickupTime: '2024-01-15 16:00',
      status: 'scheduled',
      qrCode: 'BR-2024-003',
      priority: 'low',
      trackingSteps: [
        { step: 'Match Approved', time: '2024-01-15 09:15', completed: true },
        { step: 'Pickup Scheduled', time: '2024-01-15 09:30', completed: true },
        { step: 'In Transit', time: '', completed: false },
        { step: 'Delivered', time: '', completed: false }
      ]
    },
    {
      id: 4,
      restaurant: 'Fresh Bites',
      foodName: 'Salad Mix',
      quantity: '8 bags',
      recipient: 'Senior Care Center',
      contactPerson: 'Robert Kim',
      phone: '+1 234-567-8903',
      region: 'East',
      pickupTime: '2024-01-15 18:00',
      status: 'delayed',
      qrCode: 'SM-2024-004',
      priority: 'high',
      trackingSteps: [
        { step: 'Match Approved', time: '2024-01-15 08:00', completed: true },
        { step: 'Pickup Scheduled', time: '2024-01-15 08:15', completed: true },
        { step: 'In Transit', time: '', completed: false },
        { step: 'Delivered', time: '', completed: false }
      ]
    }
  ];

  const regions = ['all', 'Central', 'North', 'South', 'East', 'West'];
  const statuses = ['all', 'scheduled', 'in_transit', 'delivered', 'delayed', 'cancelled'];

  const filteredDonations = systemDonations.filter(donation => {
    const statusMatch = filterStatus === 'all' || donation.status === filterStatus;
    const regionMatch = filterRegion === 'all' || donation.region === filterRegion;
    return statusMatch && regionMatch;
  });

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'scheduled': return 'bg-blue-100 text-blue-800';
      case 'in_transit': return 'bg-yellow-100 text-yellow-800';
      case 'delivered': return 'bg-green-100 text-green-800';
      case 'delayed': return 'bg-orange-100 text-orange-800';
      case 'cancelled': return 'bg-red-100 text-red-800';
      default: return 'bg-gray-100 text-gray-800';
    }
  };

  const getPriorityColor = (priority: string) => {
    switch (priority) {
      case 'high': return 'bg-red-100 text-red-800';
      case 'medium': return 'bg-yellow-100 text-yellow-800';
      case 'low': return 'bg-green-100 text-green-800';
      default: return 'bg-gray-100 text-gray-800';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'scheduled': return 'Pickup Scheduled';
      case 'in_transit': return 'In Transit';
      case 'delivered': return 'Delivered';
      case 'delayed': return 'Delayed';
      case 'cancelled': return 'Cancelled';
      default: return 'Unknown';
    }
  };

  const getStatusCounts = () => {
    return {
      scheduled: systemDonations.filter(d => d.status === 'scheduled').length,
      in_transit: systemDonations.filter(d => d.status === 'in_transit').length,
      delivered: systemDonations.filter(d => d.status === 'delivered').length,
      delayed: systemDonations.filter(d => d.status === 'delayed').length,
    };
  };

  const statusCounts = getStatusCounts();

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900">System-Wide Tracking</h1>
          <p className="text-gray-600 mt-1">Monitor all food donations and deliveries across the platform</p>
        </div>

        {/* Status Overview */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div className="bg-white p-6 rounded-lg shadow-lg">
            <div className="flex items-center">
              <Clock className="h-8 w-8 text-blue-600 mr-3" />
              <div>
                <p className="text-sm text-gray-600">Scheduled</p>
                <p className="text-2xl font-bold text-blue-600">{statusCounts.scheduled}</p>
              </div>
            </div>
          </div>
          <div className="bg-white p-6 rounded-lg shadow-lg">
            <div className="flex items-center">
              <MapPin className="h-8 w-8 text-yellow-600 mr-3" />
              <div>
                <p className="text-sm text-gray-600">In Transit</p>
                <p className="text-2xl font-bold text-yellow-600">{statusCounts.in_transit}</p>
              </div>
            </div>
          </div>
          <div className="bg-white p-6 rounded-lg shadow-lg">
            <div className="flex items-center">
              <CheckCircle className="h-8 w-8 text-green-600 mr-3" />
              <div>
                <p className="text-sm text-gray-600">Delivered</p>
                <p className="text-2xl font-bold text-green-600">{statusCounts.delivered}</p>
              </div>
            </div>
          </div>
          <div className="bg-white p-6 rounded-lg shadow-lg">
            <div className="flex items-center">
              <AlertTriangle className="h-8 w-8 text-orange-600 mr-3" />
              <div>
                <p className="text-sm text-gray-600">Delayed</p>
                <p className="text-2xl font-bold text-orange-600">{statusCounts.delayed}</p>
              </div>
            </div>
          </div>
        </div>

        {/* Filters */}
        <div className="bg-white rounded-lg shadow-lg p-6 mb-8">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
              <select
                value={filterStatus}
                onChange={(e) => setFilterStatus(e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              >
                {statuses.map(status => (
                  <option key={status} value={status}>
                    {status === 'all' ? 'All Statuses' : getStatusText(status)}
                  </option>
                ))}
              </select>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">Filter by Region</label>
              <select
                value={filterRegion}
                onChange={(e) => setFilterRegion(e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              >
                {regions.map(region => (
                  <option key={region} value={region}>
                    {region === 'all' ? 'All Regions' : region}
                  </option>
                ))}
              </select>
            </div>

            <div className="flex items-end">
              <button className="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors flex items-center justify-center">
                <Filter className="h-4 w-4 mr-2" />
                Apply Filters
              </button>
            </div>
          </div>
        </div>

        {/* Donations List */}
        <div className="space-y-6">
          {filteredDonations.map((donation) => (
            <div key={donation.id} className="bg-white rounded-lg shadow-lg p-6">
              <div className="flex justify-between items-start mb-4">
                <div>
                  <h3 className="text-xl font-semibold text-gray-900">{donation.foodName}</h3>
                  <p className="text-gray-600">
                    {donation.restaurant} → {donation.recipient}
                  </p>
                  <p className="text-sm text-gray-500">{donation.region} Region</p>
                </div>
                <div className="flex space-x-2">
                  <span className={`px-2 py-1 rounded-full text-xs font-medium ${getPriorityColor(donation.priority)}`}>
                    {donation.priority} priority
                  </span>
                  <span className={`px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(donation.status)}`}>
                    {getStatusText(donation.status)}
                  </span>
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div className="space-y-3">
                  <div className="flex items-center">
                    <Package className="h-5 w-5 text-gray-400 mr-3" />
                    <div>
                      <p className="text-sm text-gray-600">Quantity</p>
                      <p className="font-medium text-gray-900">{donation.quantity}</p>
                    </div>
                  </div>
                  <div className="flex items-center">
                    <User className="h-5 w-5 text-gray-400 mr-3" />
                    <div>
                      <p className="text-sm text-gray-600">Contact Person</p>
                      <p className="font-medium text-gray-900">{donation.contactPerson}</p>
                    </div>
                  </div>
                </div>

                <div className="space-y-3">
                  <div className="flex items-center">
                    <Phone className="h-5 w-5 text-gray-400 mr-3" />
                    <div>
                      <p className="text-sm text-gray-600">Phone</p>
                      <p className="font-medium text-gray-900">{donation.phone}</p>
                    </div>
                  </div>
                  <div className="flex items-center">
                    <Calendar className="h-5 w-5 text-gray-400 mr-3" />
                    <div>
                      <p className="text-sm text-gray-600">Pickup Time</p>
                      <p className="font-medium text-gray-900">{donation.pickupTime}</p>
                    </div>
                  </div>
                </div>

                <div className="space-y-3">
                  <div className="flex items-center">
                    <QrCode className="h-5 w-5 text-gray-400 mr-3" />
                    <div>
                      <p className="text-sm text-gray-600">QR Code</p>
                      <p className="font-medium text-gray-900">{donation.qrCode}</p>
                    </div>
                  </div>
                  <div className="flex items-center">
                    <MapPin className="h-5 w-5 text-gray-400 mr-3" />
                    <div>
                      <p className="text-sm text-gray-600">Region</p>
                      <p className="font-medium text-gray-900">{donation.region}</p>
                    </div>
                  </div>
                </div>
              </div>

              {/* Tracking Timeline */}
              <div className="border-t border-gray-200 pt-4">
                <h4 className="text-sm font-medium text-gray-900 mb-3">Tracking Timeline</h4>
                <div className="flex space-x-8">
                  {donation.trackingSteps.map((step, index) => (
                    <div key={index} className="flex items-center">
                      <div className="flex-shrink-0">
                        {step.completed ? (
                          <div className="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                            <CheckCircle className="h-3 w-3 text-white" />
                          </div>
                        ) : (
                          <div className="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                            <div className="w-2 h-2 bg-white rounded-full"></div>
                          </div>
                        )}
                      </div>
                      <div className="ml-2">
                        <p className={`text-xs font-medium ${step.completed ? 'text-gray-900' : 'text-gray-500'}`}>
                          {step.step}
                        </p>
                        {step.time && (
                          <p className="text-xs text-gray-500">{step.time}</p>
                        )}
                      </div>
                      {index < donation.trackingSteps.length - 1 && (
                        <div className="ml-4 w-8 h-0.5 bg-gray-300"></div>
                      )}
                    </div>
                  ))}
                </div>
              </div>

              {/* Admin Actions */}
              {(donation.status === 'delayed' || donation.status === 'in_transit') && (
                <div className="border-t border-gray-200 pt-4 mt-4">
                  <div className="flex space-x-2">
                    <button className="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors">
                      View Live Location
                    </button>
                    <button className="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition-colors">
                      Contact Volunteer
                    </button>
                    {donation.status === 'delayed' && (
                      <button className="bg-orange-600 text-white px-3 py-1 rounded text-sm hover:bg-orange-700 transition-colors">
                        Escalate Issue
                      </button>
                    )}
                  </div>
                </div>
              )}
            </div>
          ))}
        </div>

        {filteredDonations.length === 0 && (
          <div className="text-center py-12">
            <Package className="mx-auto h-12 w-12 text-gray-400 mb-4" />
            <h3 className="text-lg font-medium text-gray-900">No donations found</h3>
            <p className="text-gray-500">Try adjusting your filters to see more results.</p>
          </div>
        )}
      </div>
    </div>
  );
};

export default AdminTracking;