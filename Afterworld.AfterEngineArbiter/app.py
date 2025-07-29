from flask import Flask, jsonify, request, send_file, render_template
from soap_utils import SoapUtils

app = Flask(__name__)
soap = SoapUtils()

@app.route('/')
def index():
    return render_template('default.html')

@app.route('/arbiter/gameserver/renderUser.php')
def render_user():
    user_id = request.args.get('userId', type=int)
    if not user_id:
        return jsonify({"error": "Missing userId"}), 400
    
    try:
        result = soap.render_user(user_id)
        return jsonify({"result": result})
    except Exception as e:
        return jsonify({"error": str(e)}), 500

@app.route('/arbiter/gameserver/renderAsset.php')
def render_asset():
    asset_id = request.args.get('assetId', type=int)
    asset_type = request.args.get('assetType', type=int)
    if not asset_id or not asset_type:
        return jsonify({"error": "Missing assetId or assetType"}), 400

    try:
        result = soap.render_asset(asset_id, asset_type)
        return jsonify({"result": result})
    except Exception as e:
        return jsonify({"error": str(e)}), 500

@app.route('/arbiter/gameserver/renderUser3D.php')
def render_user_3d():
    user_id = request.args.get('userId', type=int)
    if not user_id:
        return jsonify({"error": "Missing userId"}), 400

    try:
        result = soap.render_user3d(user_id)
        return jsonify({"result": result})
    except Exception as e:
        return jsonify({"error": str(e)}), 500

@app.route('/arbiter/gameserver/getAllRccs.php')
def get_all_rccs():
    return jsonify(soap.get_all_rccs())

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=34744)