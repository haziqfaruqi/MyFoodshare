import React, { useState } from 'react';
import { AlertTriangle, Search, Filter, Eye, MessageSquare, CheckCircle, XCircle, Clock, User, Calendar } from 'lucide-react';

const IssueManagement: React.FC = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [filterPriority, setFilterPriority] = useState('all');
  const [filterStatus, setFilterStatus] = useState('all');
  const [selectedIssue, setSelectedIssue] = useState<number | null>(null);

  const issues = [
    {
      id: 1,
      title: 'Quality complaint from recipient',
      description: 'Recipient reported that the food received was not fresh and had an unusual smell. This is the second complaint from this recipient organization.',
      restaurant: 'Fast Bites',
      restaurantOwner: 'Mike Johnson',
      recipient: 'Hope Community Center',
      reportedBy: 'Sarah Wilson',
      priority: 'high',
      status: 'open',
      category: 'quality',
      createdAt: '2024-01-20 14:30',
      updatedAt: '2024-01-20 15:45',
      assignedTo: 'Admin Team',
      comments: [
        {
          id: 1,
          author: 'Sarah Wilson',
          message: 'The food had a strange odor and some items appeared to be past their expiration date.',
          timestamp: '2024-01-20 14:30'
        },
        {
          id: 2,
          author: 'Admin Team',
          message: 'We have contacted the restaurant to investigate this issue. Awaiting their response.',
          timestamp: '2024-01-20 15:45'
        }
      ]
    },
    {
      id: 2,
      title: 'Multiple no-shows for pickup',
      description: 'Restaurant has reported that the matched recipient has failed to show up for pickup three times in the past week.',
      restaurant: 'Corner Café',
      restaurantOwner: 'Lisa Chen',
      recipient: 'Downtown Shelter',
      reportedBy: 'Lisa Chen',
      priority: 'medium',
      status: 'investigating',
      category: 'logistics',
      createdAt: '2024-01-19 10:15',
      updatedAt: '2024-01-20 09:30',
      assignedTo: 'Operations Team',
      comments: [
        {
          id: 1,
          author: 'Lisa Chen',
          message: 'This is the third time this week that Downtown Shelter has not shown up for scheduled pickups.',
          timestamp: '2024-01-19 10:15'
        }
      ]
    },
    {
      id: 3,
      title: 'Incorrect quantity reported',
      description: 'Discrepancy between the quantity listed by the restaurant and what was actually available for pickup.',
      restaurant: 'Fresh Foods',
      restaurantOwner: 'David Kim',
      recipient: 'Family Support Center',
      reportedBy: 'Maria Rodriguez',
      priority: 'low',
      status: 'resolved',
      category: 'data',
      createdAt: '2024-01-18 16:20',
      updatedAt: '2024-01-19 11:00',
      assignedTo: 'Data Team',
      comments: [
        {
          id: 1,
          author: 'Maria Rodriguez',
          message: 'Listed 50 sandwiches but only 30 were available. This caused confusion during pickup.',
          timestamp: '2024-01-18 16:20'
        },
        {
          id: 2,
          author: 'Data Team',
          message: 'Issue resolved. Restaurant has been reminded about accurate quantity reporting.',
          timestamp: '2024-01-19 11:00'
        }
      ]
    },
    {
      id: 4,
      title: 'System access issues',
      description: 'Restaurant owner unable to log into the platform for the past 24 hours. Password reset attempts have failed.',
      restaurant: 'Golden Spoon',
      restaurantOwner: 'John Smith',
      recipient: 'N/A',
      reportedBy: 'John Smith',
      priority: 'high',
      status: 'open',
      category: 'technical',
      createdAt: '2024-01-20 08:45',
      updatedAt: '2024-01-20 08:45',
      assignedTo: 'Technical Team',
      comments: [
        {
          id: 1,
          author: 'John Smith',
          message: 'Cannot access my account. Password reset emails are not being received.',
          timestamp: '2024-01-20 08:45'
        }
      ]
    }
  ];

  const priorities = ['all', 'high', 'medium', 'low'];
  const statuses = ['all', 'open', 'investigating', 'resolved', 'closed'];

  const filteredIssues = issues.filter(issue => {
    const matchesSearch = issue.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         issue.restaurant.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         issue.description.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesPriority = filterPriority === 'all' || issue.priority === filterPriority;
    const matchesStatus = filterStatus === 'all' || issue.status === filterStatus;
    
    return matchesSearch && matchesPriority && matchesStatus;
  });

  const getPriorityColor = (priority: string) => {
    switch (priority) {
      case 'high': return 'bg-red-100 text-red-800';
      case 'medium': return 'bg-yellow-100 text-yellow-800';
      case 'low': return 'bg-green-100 text-green-800';
      default: return 'bg-gray-100 text-gray-800';
    }
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'open': return 'bg-red-100 text-red-800';
      case 'investigating': return 'bg-yellow-100 text-yellow-800';
      case 'resolved': return 'bg-green-100 text-green-800';
      case 'closed': return 'bg-gray-100 text-gray-800';
      default: return 'bg-gray-100 text-gray-800';
    }
  };

  const getCategoryIcon = (category: string) => {
    switch (category) {
      case 'quality': return <AlertTriangle className="h-4 w-4" />;
      case 'logistics': return <Clock className="h-4 w-4" />;
      case 'technical': return <AlertTriangle className="h-4 w-4" />;
      case 'data': return <AlertTriangle className="h-4 w-4" />;
      default: return <AlertTriangle className="h-4 w-4" />;
    }
  };

  const handleStatusChange = (issueId: number, newStatus: string) => {
    console.log(`Changing issue ${issueId} status to ${newStatus}`);
    // Handle status change logic here
  };

  const selectedIssueData = issues.find(issue => issue.id === selectedIssue);

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-8">
          <div className="flex items-center mb-4">
            <AlertTriangle className="h-8 w-8 text-red-600 mr-3" />
            <div>
              <h1 className="text-3xl font-bold text-gray-900">Issue Management</h1>
              <p className="text-gray-600 mt-1">Track and resolve platform issues and user complaints</p>
            </div>
          </div>
        </div>

        {/* Stats Overview */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div className="bg-white p-6 rounded-lg shadow-lg">
            <div className="flex items-center">
              <AlertTriangle className="h-8 w-8 text-red-600 mr-3" />
              <div>
                <p className="text-sm text-gray-600">Open Issues</p>
                <p className="text-2xl font-bold text-red-600">{issues.filter(i => i.status === 'open').length}</p>
              </div>
            </div>
          </div>
          <div className="bg-white p-6 rounded-lg shadow-lg">
            <div className="flex items-center">
              <Clock className="h-8 w-8 text-yellow-600 mr-3" />
              <div>
                <p className="text-sm text-gray-600">Investigating</p>
                <p className="text-2xl font-bold text-yellow-600">{issues.filter(i => i.status === 'investigating').length}</p>
              </div>
            </div>
          </div>
          <div className="bg-white p-6 rounded-lg shadow-lg">
            <div className="flex items-center">
              <CheckCircle className="h-8 w-8 text-green-600 mr-3" />
              <div>
                <p className="text-sm text-gray-600">Resolved</p>
                <p className="text-2xl font-bold text-green-600">{issues.filter(i => i.status === 'resolved').length}</p>
              </div>
            </div>
          </div>
          <div className="bg-white p-6 rounded-lg shadow-lg">
            <div className="flex items-center">
              <AlertTriangle className="h-8 w-8 text-red-600 mr-3" />
              <div>
                <p className="text-sm text-gray-600">High Priority</p>
                <p className="text-2xl font-bold text-red-600">{issues.filter(i => i.priority === 'high').length}</p>
              </div>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Issues List */}
          <div className="lg:col-span-2">
            {/* Filters */}
            <div className="bg-white rounded-lg shadow-lg p-6 mb-6">
              <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">Search</label>
                  <div className="relative">
                    <input
                      type="text"
                      value={searchTerm}
                      onChange={(e) => setSearchTerm(e.target.value)}
                      className="w-full px-3 py-2 pl-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Search issues..."
                    />
                    <Search className="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                  </div>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                  <select
                    value={filterPriority}
                    onChange={(e) => setFilterPriority(e.target.value)}
                    className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  >
                    {priorities.map(priority => (
                      <option key={priority} value={priority}>
                        {priority === 'all' ? 'All Priorities' : priority.charAt(0).toUpperCase() + priority.slice(1)}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">Status</label>
                  <select
                    value={filterStatus}
                    onChange={(e) => setFilterStatus(e.target.value)}
                    className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  >
                    {statuses.map(status => (
                      <option key={status} value={status}>
                        {status === 'all' ? 'All Statuses' : status.charAt(0).toUpperCase() + status.slice(1)}
                      </option>
                    ))}
                  </select>
                </div>
              </div>
            </div>

            {/* Issues List */}
            <div className="space-y-4">
              {filteredIssues.map((issue) => (
                <div
                  key={issue.id}
                  className={`bg-white rounded-lg shadow-lg p-6 cursor-pointer transition-all ${
                    selectedIssue === issue.id ? 'ring-2 ring-blue-500' : 'hover:shadow-xl'
                  }`}
                  onClick={() => setSelectedIssue(issue.id)}
                >
                  <div className="flex justify-between items-start mb-3">
                    <div className="flex items-center">
                      {getCategoryIcon(issue.category)}
                      <h3 className="text-lg font-semibold text-gray-900 ml-2">{issue.title}</h3>
                    </div>
                    <div className="flex space-x-2">
                      <span className={`px-2 py-1 rounded-full text-xs font-medium ${getPriorityColor(issue.priority)}`}>
                        {issue.priority}
                      </span>
                      <span className={`px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(issue.status)}`}>
                        {issue.status}
                      </span>
                    </div>
                  </div>

                  <p className="text-gray-600 mb-3 line-clamp-2">{issue.description}</p>

                  <div className="grid grid-cols-2 gap-4 text-sm text-gray-500">
                    <div>
                      <p><strong>Restaurant:</strong> {issue.restaurant}</p>
                      <p><strong>Reported by:</strong> {issue.reportedBy}</p>
                    </div>
                    <div>
                      <p><strong>Created:</strong> {issue.createdAt}</p>
                      <p><strong>Assigned to:</strong> {issue.assignedTo}</p>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Issue Details */}
          <div className="lg:col-span-1">
            {selectedIssueData ? (
              <div className="bg-white rounded-lg shadow-lg p-6">
                <div className="flex justify-between items-start mb-4">
                  <h2 className="text-xl font-semibold text-gray-900">Issue Details</h2>
                  <button className="text-blue-600 hover:text-blue-800">
                    <Eye className="h-4 w-4" />
                  </button>
                </div>

                <div className="space-y-4">
                  <div>
                    <h3 className="font-medium text-gray-900">{selectedIssueData.title}</h3>
                    <p className="text-sm text-gray-600 mt-1">{selectedIssueData.description}</p>
                  </div>

                  <div className="grid grid-cols-2 gap-4 text-sm">
                    <div>
                      <p className="text-gray-500">Priority</p>
                      <span className={`inline-block px-2 py-1 rounded-full text-xs font-medium ${getPriorityColor(selectedIssueData.priority)}`}>
                        {selectedIssueData.priority}
                      </span>
                    </div>
                    <div>
                      <p className="text-gray-500">Status</p>
                      <span className={`inline-block px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(selectedIssueData.status)}`}>
                        {selectedIssueData.status}
                      </span>
                    </div>
                  </div>

                  <div className="space-y-2 text-sm">
                    <div>
                      <p className="text-gray-500">Restaurant</p>
                      <p className="font-medium">{selectedIssueData.restaurant}</p>
                      <p className="text-gray-600">Owner: {selectedIssueData.restaurantOwner}</p>
                    </div>
                    {selectedIssueData.recipient !== 'N/A' && (
                      <div>
                        <p className="text-gray-500">Recipient</p>
                        <p className="font-medium">{selectedIssueData.recipient}</p>
                      </div>
                    )}
                    <div>
                      <p className="text-gray-500">Reported by</p>
                      <p className="font-medium">{selectedIssueData.reportedBy}</p>
                    </div>
                    <div>
                      <p className="text-gray-500">Assigned to</p>
                      <p className="font-medium">{selectedIssueData.assignedTo}</p>
                    </div>
                  </div>

                  {/* Status Actions */}
                  {selectedIssueData.status === 'open' && (
                    <div className="space-y-2">
                      <button
                        onClick={() => handleStatusChange(selectedIssueData.id, 'investigating')}
                        className="w-full bg-yellow-600 text-white px-3 py-2 rounded text-sm hover:bg-yellow-700 transition-colors"
                      >
                        Start Investigation
                      </button>
                      <button
                        onClick={() => handleStatusChange(selectedIssueData.id, 'resolved')}
                        className="w-full bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700 transition-colors"
                      >
                        Mark as Resolved
                      </button>
                    </div>
                  )}

                  {selectedIssueData.status === 'investigating' && (
                    <div className="space-y-2">
                      <button
                        onClick={() => handleStatusChange(selectedIssueData.id, 'resolved')}
                        className="w-full bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700 transition-colors"
                      >
                        Mark as Resolved
                      </button>
                      <button
                        onClick={() => handleStatusChange(selectedIssueData.id, 'open')}
                        className="w-full bg-gray-600 text-white px-3 py-2 rounded text-sm hover:bg-gray-700 transition-colors"
                      >
                        Reopen Issue
                      </button>
                    </div>
                  )}

                  {/* Comments */}
                  <div className="border-t pt-4">
                    <h4 className="font-medium text-gray-900 mb-3 flex items-center">
                      <MessageSquare className="h-4 w-4 mr-2" />
                      Comments ({selectedIssueData.comments.length})
                    </h4>
                    <div className="space-y-3 max-h-64 overflow-y-auto">
                      {selectedIssueData.comments.map((comment) => (
                        <div key={comment.id} className="bg-gray-50 p-3 rounded">
                          <div className="flex items-center justify-between mb-1">
                            <span className="text-sm font-medium text-gray-900">{comment.author}</span>
                            <span className="text-xs text-gray-500">{comment.timestamp}</span>
                          </div>
                          <p className="text-sm text-gray-600">{comment.message}</p>
                        </div>
                      ))}
                    </div>
                  </div>
                </div>
              </div>
            ) : (
              <div className="bg-white rounded-lg shadow-lg p-12 text-center">
                <AlertTriangle className="h-12 w-12 text-gray-400 mx-auto mb-4" />
                <p className="text-gray-600">Select an issue to view details</p>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default IssueManagement;