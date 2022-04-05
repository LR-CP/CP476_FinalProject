CREATE DATABASE stocks;

CREATE TABLE watchlist (
  PID int NOT NULL AUTO_INCREMENT,
  symbol VARCHAR(6) NOT NULL,
  company_name VARCHAR(255) NOT NULL,
  current_price float DEFAULT NULL,
  predicted_price float DEFAULT NULL,
  price_difference float DEFAULT NULL,
  PRIMARY KEY (PID),
  FOREIGN KEY (symbol) REFERENCES tickerInfo(symbol)
);

CREATE TABLE tickerInfo (
  symbol VARCHAR(6) NOT NULL,
  fiftyTwoWeekHigh float NOT NULL,
  trailingPE float DEFAULT NULL,
  pegRatio float DEFAULT NULL,
  priceToSales float DEFAULT NULL,
  epsForward float DEFAULT NULL,
  epsCurrentYear float DEFAULT NULL,
  bookValue float DEFAULT NULL,
  forwardPE float DEFAULT NULL,
  priceToBook float DEFAULT NULL,
  targetPriceHigh float DEFAULT NULL,
  targetPriceLow float DEFAULT NULL,
  targetPriceMean float DEFAULT NULL,
  PRIMARY KEY (symbol)
);