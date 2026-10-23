from sklearn.linear_model import LinearRegression
import numpy as np
import json
import sys

# Historical waste levels used to train the model
days = np.array([1, 2, 3, 4, 5]).reshape(-1, 1)
fill_levels = np.array([30, 45, 55, 70, 80])

model = LinearRegression()
model.fit(days, fill_levels)

# Estimate the fill level for the next day
next_day_level = model.predict(np.array([[6]]))[0]

# Read current bin levels supplied by the dashboard
try:
    bins = json.loads(sys.stdin.read())
except (json.JSONDecodeError, ValueError):
    bins = []

predictions = []

for bin_data in bins:
    current_fill = float(bin_data.get("fill_level", 0))

    # Estimate the next day's level
    predicted = current_fill + (next_day_level - fill_levels[-1])

    # Keep the result between 0% and 100%
    predicted = max(0, min(100, predicted))

    predictions.append({
        "bin_id": bin_data.get("bin_id"),
        "predicted_level": round(predicted, 1)
    })

# Send the results back to PHP as JSON
print(json.dumps(predictions))