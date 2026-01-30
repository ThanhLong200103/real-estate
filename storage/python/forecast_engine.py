import sys
import json
import pandas as pd
from prophet import Prophet
import warnings

# Tắt các cảnh báo không cần thiết
warnings.filterwarnings("ignore")

def make_forecast():
    try:
        # 1. Đọc dữ liệu từ Laravel truyền qua tham số (JSON)
        input_data = json.loads(sys.argv[1])
        
        if len(input_data) < 3:
            return json.dumps({"error": "Dữ liệu quá ít để dự báo (cần tối thiểu 3 tháng)"})

        # 2. Chuẩn bị DataFrame cho Prophet
        df = pd.DataFrame(input_data)
        df['ds'] = pd.to_datetime(df['ds'])
        df['y'] = pd.to_numeric(df['y'])

        # 3. Khởi tạo và huấn luyện mô hình
        # yearly_seasonality: Học theo biến động hàng năm
        model = Prophet(yearly_seasonality=True, weekly_seasonality=False, daily_seasonality=False)
        model.fit(df)

        # 4. Dự báo cho 12 tháng tiếp theo
        future = model.make_future_dataframe(periods=12, freq='MS')
        forecast = model.predict(future)

        # 5. Trích xuất giá trị
        current_val = df['y'].iloc[-1]
        
        # Lấy dự báo 1 tháng, 3 tháng (1 quý), và 12 tháng (1 năm)
        # forecast đã bao gồm cả dữ liệu cũ và mới
        idx_now = len(df) - 1
        val_1m = forecast['yhat'].iloc[idx_now + 1]
        val_3m = forecast['yhat'].iloc[idx_now + 3]
        val_12m = forecast['yhat'].iloc[idx_now + 12]

        # 6. Tính % tăng giảm
        res = {
            "current": round(float(current_val), 2),
            "month": round(((val_1m - current_val) / current_val) * 100, 2),
            "quarter": round(((val_3m - current_val) / current_val) * 100, 2),
            "year": round(((val_12m - current_val) / current_val) * 100, 2),
            "forecast_value": round(float(val_1m), 2), # Giá trị tuyệt đối của tháng tới
            "history": input_data # Gửi lại dữ liệu cũ để JS vẽ phần lịch sử
        }
        
        return json.dumps(res)

    except Exception as e:
        return json.dumps({"error": str(e)})

if __name__ == "__main__":
    print(make_forecast())