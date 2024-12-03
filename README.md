# News Aggregator API Service

A simple News Aggregator API service that integrates articles from **New York Times**, **The Guardian**, and **NewsAPI**. This project provides an efficient backend solution for aggregating and serving news articles, complete with APIs for filtering, searching, and user preferences.

## Features

- **Data Aggregation**: Fetches news articles from three major sources.
- **Database Storage**: Articles are stored locally for efficient querying.
- **Scheduled Updates**: Articles are automatically updated every hour.
- **Search & Filter**: API endpoints support advanced filtering by date, category, source, and more.
- **User Preferences**: Allows saving and retrieving user-specific preferences.

---

## Getting Started

Follow the steps below to set up and run the service:

### Prerequisites

- **PHP 8.x**
- **Composer**
- **MySQL**
- **Laravel** (version 9.x or higher)
- **Redis** (for Horizon)

---

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/news-aggregator.git
   cd news-aggregator
