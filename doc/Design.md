# WebComponents Design

```mermaid
graph TD
    App --> Info
    App --> Standard
    App --> ResultsView
    App --> Member
    App --> Moderator
    App --> Admin
    
    App --> Login

    Info --> Article
    
    Standard --> Sections
    Sections --> Section
    Section --> Breeds
    Breeds --> Breed
    Breed --> Colors
    Colors --> Color
    
    ResultsView --> DonutChart
    ResultsView --> GeoChart
    ResultsView --> TimeLineChart
    ResultsView --> ResultsDoc
    
    Member --> MemberDetails
    Member --> Reports
    Reports --> Report
    
    Moderator --> Districts
    Districts --> District
    District --> Results
    District --> Members
    
    Members --> Member
    
    Admin --> Districts
    Admin --> Articles
    Admin --> Logs

    Articles --> Article
```