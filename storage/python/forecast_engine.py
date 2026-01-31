import sys
import json
import pandas as pd
from prophet import Prophet
import warnings
import numpy as np


warnings.filterwarnings("ignore")

def make_forecast():
    try:
        # 1. Nhận dữ liệu lịch sử từ PHP
        input_data = json.loads(sys.argv[1])

        if len(input_data) < 3:
            return json.dumps({
                "error": "Dữ liệu quá ít để dự báo (cần tối thiểu 3 tháng)"
            })

        # 2. Chuẩn bị DataFrame cho Prophet
        df = pd.DataFrame(input_data)
        df['ds'] = pd.to_datetime(df['ds'])
        df['y'] = pd.to_numeric(df['y'])

        # 3. Train model
        model = Prophet(
            yearly_seasonality=True,
            weekly_seasonality=False,
            daily_seasonality=False
        )
        model.fit(df)

        # 4. Tạo 12 tháng tương lai
        future = model.make_future_dataframe(periods=12, freq='MS')

        # 5. Predict
        forecast = model.predict(future)[
          ['ds', 'yhat', 'yhat_lower', 'yhat_upper']
        ]


        # 6. Giá hiện tại
        current_val = df['y'].iloc[-1]
        idx_now = len(df) - 1

        # 7. Giá dự báo
        val_1m  = forecast['yhat'].iloc[idx_now + 1]
        val_3m  = forecast['yhat'].iloc[idx_now + 3]
        val_12m = forecast['yhat'].iloc[idx_now + 12]

        # 8. 🔥 12 tháng tương lai cho chart
        future_12 = forecast.tail(12)

        future_12["yhat"] = future_12["yhat"] * (
         1 + np.random.normal(0, 0.01, len(future_12))
        )

        # 9. Chuẩn hoá history giống fallback
        history = [
            {
                "ds": pd.to_datetime(item["ds"]).strftime("%Y-%m"),
                "y": round(float(item["y"]), 2)
            }
            for item in input_data
        ]

        res = {
            "current": round(float(current_val), 2),

            "month": round(((val_1m - current_val) / current_val) * 100, 2),
            "quarter": round(((val_3m - current_val) / current_val) * 100, 2),
            "year": round(((val_12m - current_val) / current_val) * 100, 2),

            # dữ liệu cho chart (ĐỒNG BỘ VỚI MarketTrendController)
            "history": history,
            "future": [
                {
                    "ds": row["ds"].strftime("%Y-%m"),
                    "y": round(float(row["yhat"]), 2)
                }
                for _, row in future_12.iterrows()
            ]
        }

        return json.dumps(res)

    except Exception as e:
        return json.dumps({"error": str(e)})


if __name__ == "__main__":
    print(make_forecast())
